<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Document;

class MediaController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('view_media'); // Uncomment after adding permission
        
        $folder = current(explode('?', $request->input('folder', '/')));
        if (str_contains($folder, '..')) abort(403);
        $diskPath = $folder === '/' ? '' : $folder;

        $files = [];
        
        // Add "Up" directory
        if ($folder !== '/' && $folder !== '') {
            $parent = dirname($folder);
            if ($parent === '\\' || $parent === '.') $parent = '/';
            $files[] = [
                'name'          => '.. (Quay lại)',
                'path'          => $parent,
                'is_dir'        => true,
                'size'          => 0,
                'last_modified' => 0,
                'mime_type'     => 'folder',
                'url'           => route('admin.media.index', ['folder' => $parent]),
            ];
        }

        $directories = Storage::disk('private')->directories($diskPath);
        foreach ($directories as $dir) {
            $files[] = [
                'name'          => basename($dir),
                'path'          => $dir,
                'is_dir'        => true,
                'size'          => 0,
                'last_modified' => Storage::disk('private')->lastModified($dir),
                'mime_type'     => 'folder',
                'url'           => route('admin.media.index', ['folder' => $dir]),
            ];
        }

        $allFiles = Storage::disk('private')->files($diskPath);
        
        // Truy vấn DB lấy thông tin bổ sung cho các file
        $filePathsForDb = array_map(function($f) { return $f; }, $allFiles);
        $documentsDb = Document::whereIn('file_path', $filePathsForDb)->get()->keyBy('file_path');

        foreach ($allFiles as $file) {
            if (basename($file) === '.gitignore') continue;

            $doc = $documentsDb->get($file);

            $files[] = [
                'name'          => basename($file),
                'path'          => $file,
                'is_dir'        => false,
                'size'          => Storage::disk('private')->size($file),
                'last_modified' => Storage::disk('private')->lastModified($file),
                'mime_type'     => Storage::mimeType('private/' . $file),
                'url'           => route('admin.media.serve', ['filename' => $file]),
                'document'      => $doc,
            ];
        }

        // Sort: Up dir first -> then directories -> then files by last modified desc
        usort($files, function($a, $b) {
            if ($a['name'] === '.. (Quay lại)') return -1;
            if ($b['name'] === '.. (Quay lại)') return 1;
            if ($a['is_dir'] !== $b['is_dir']) return $a['is_dir'] ? -1 : 1;
            return $b['last_modified'] <=> $a['last_modified'];
        });

        $search = $request->input('search');
        if ($search) {
            $files = array_filter($files, function($file) use ($search) {
                if ($file['name'] === '.. (Quay lại)') return true;
                return stripos($file['name'], $search) !== false;
            });
        }

        $type = $request->input('type');
        if ($type && $type !== 'all') {
            $files = array_filter($files, function($file) use ($type) {
                if ($file['is_dir']) return true; // keep folders when filtering types
                $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                switch ($type) {
                    case 'word': return in_array($ext, ['doc', 'docx']);
                    case 'pdf': return in_array($ext, ['pdf']);
                    case 'excel': return in_array($ext, ['xls', 'xlsx', 'csv']);
                    case 'image': return in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                    case 'video': return in_array($ext, ['mp4', 'mov', 'avi']);
                    case 'archive': return in_array($ext, ['zip', 'rar', '7z']);
                    default: return true;
                }
            });
        }

        $perPage = $request->input('per_page', 20);
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $currentItems = array_slice($files, ($currentPage - 1) * $perPage, $perPage);
        $paginatedFiles = new LengthAwarePaginator($currentItems, count($files), $perPage, $currentPage, [
            'path' => request()->url(),
            'query' => request()->query(),
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'files' => $paginatedFiles,
                'search' => $search,
                'type' => $type,
                'currentFolder' => $folder,
                'documentTypes' => Document::$types
            ]);
        }

        return view('admin.media.index', [
            'files' => $paginatedFiles, 
            'search' => $search, 
            'type' => $type,
            'currentFolder' => $folder,
            'documentTypes' => Document::$types
        ]);
    }

    public function store(Request $request)
    {
        $this->authorize('create_media');
        
        $request->validate([
            'files' => 'required|array',
            'files.*' => 'file|max:10240', // Max 10MB per file
            'folder' => 'nullable|string',
            'document_type' => 'required|string',
            'title' => 'nullable|string|max:255',
            'notes' => 'nullable|string'
        ]);

        $folder = $request->input('folder', '/');
        if (str_contains($folder, '..')) abort(403);
        $diskPath = $folder === '/' ? '' : $folder;

        if ($request->hasFile('files')) {
            $count = 0;
            foreach ($request->file('files') as $file) {
                $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $extension = $file->getClientOriginalExtension();
                $cleanName = \Str::slug($originalName) . '.' . $extension;
                
                $filename = time() . '_' . $count . '_' . $cleanName;
                
                $path = $file->storeAs($diskPath, $filename, 'private');
                
                Document::create([
                    'uploaded_by' => auth()->id(),
                    'document_type' => $request->input('document_type'),
                    'title' => $request->input('title') ?: $originalName,
                    'file_path' => $path,
                    'file_name' => $originalName . '.' . $extension,
                    'file_size' => $file->getSize(),
                    'mime_type' => $file->getMimeType(),
                    'notes' => $request->input('notes'),
                ]);

                $count++;
            }
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => true, 'message' => 'Tải lên ' . $count . ' tập tin thành công.']);
            }
            
            return redirect()->route('admin.media.index', ['folder' => $folder])->with('success', 'Tải lên ' . $count . ' tập tin thành công.');
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => false, 'message' => 'Vui lòng chọn ít nhất một tập tin.'], 422);
        }

        return back()->withErrors('Vui lòng chọn ít nhất một tập tin.');
    }

    public function createFolder(Request $request)
    {
        $this->authorize('create_media');

        $request->validate([
            'folder_name' => 'required|string|max:255',
            'current_folder' => 'nullable|string',
        ]);

        $currentFolder = $request->input('current_folder', '/');
        if (str_contains($currentFolder, '..')) abort(403);
        
        $folderName = \Str::slug($request->input('folder_name'));
        $diskPath = ($currentFolder === '/' ? '' : $currentFolder . '/') . $folderName;

        if (!Storage::disk('private')->exists($diskPath)) {
            Storage::disk('private')->makeDirectory($diskPath);
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => true, 'message' => 'Tạo thư mục thành công.']);
            }
            return redirect()->route('admin.media.index', ['folder' => $currentFolder])->with('success', 'Tạo thư mục thành công.');
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => false, 'message' => 'Thư mục đã tồn tại.'], 422);
        }

        return back()->withErrors('Thư mục đã tồn tại.');
    }

    public function mapFile(Request $request)
    {
        $this->authorize('create_media');

        $request->validate([
            'file_path' => 'required|string',
            'document_type' => 'required|string',
            'title' => 'required|string|max:255',
            'notes' => 'nullable|string'
        ]);

        $filePath = $request->input('file_path');
        if (str_contains($filePath, '..') || !Storage::disk('private')->exists($filePath)) {
            abort(403, 'File không tồn tại hoặc không hợp lệ.');
        }

        // Tự động gán quyền update nếu là updateOrCreate?
        // Không, vì user đang thực hiện mapFile, chỉ cần create_media.
        // Tuy nhiên, nếu user đang sửa file đã map, cần check update_media
        $existing = Document::where('file_path', $filePath)->first();
        if ($existing) {
            $this->authorize('update_media');
        }

        Document::updateOrCreate(
            ['file_path' => $filePath],
            [
                'uploaded_by' => auth()->id(), // Update người sửa cuối?
                'document_type' => $request->input('document_type'),
                'title' => $request->input('title'),
                'file_name' => basename($filePath),
                'file_size' => Storage::disk('private')->size($filePath),
                'mime_type' => Storage::mimeType('private/' . $filePath),
                'notes' => $request->input('notes'),
            ]
        );

        return back()->with('success', 'Đã lưu thông tin định danh file thành công.');
    }

    public function bulkDestroy(Request $request)
    {
        $this->authorize('delete_media');

        $paths = $request->input('paths', []);
        
        if (empty($paths)) {
            return back()->withErrors('Không có tệp/thư mục nào được chọn.');
        }

        // Validate paths roughly to prevent crossing up dirs
        foreach ($paths as $path) {
            if (str_contains($path, '..')) abort(403);
            
            // Xoá vật lý trên disk
            if (Storage::disk('private')->exists($path)) {
                // Check if it's a directory
                $isDir = count(Storage::disk('private')->directories($path)) > 0 || count(Storage::disk('private')->files($path)) > 0 || is_dir(storage_path('app/private/' . $path));
                
                // Fallback directory check: try to list its files. If it's a file, this returns empty.
                // A better cross-platform way in Laravel storage is to check if it's in directories() of parent.
                $parent = dirname($path);
                if ($parent === '\\' || $parent === '.') $parent = '';
                $isDirFallback = in_array($path, Storage::disk('private')->directories($parent));
                
                if ($isDirFallback) {
                    Storage::disk('private')->deleteDirectory($path);
                    Document::where('file_path', 'like', $path . '/%')->delete();
                } else {
                    Storage::disk('private')->delete($path);
                    Document::where('file_path', $path)->delete();
                }
            }
        }

        return back()->with('success', 'Đã xóa thành công các mục được chọn.');
    }

    public function moveItems(Request $request)
    {
        $this->authorize('update_media'); // Need update or create to move? Update makes sense for modifying existing stuff. Wait, let's use create_media/update_media together or just delete+create. Let's use update_media.

        $paths = $request->input('paths', []);
        $destination = $request->input('destination_folder', ''); // Can be '/'

        if (str_contains($destination, '..')) abort(403);
        
        if (empty($paths)) {
            return back()->withErrors('Không có tệp/thư mục nào được chọn để di chuyển.');
        }

        $diskDestination = $destination === '/' ? '' : $destination;
        
        if ($diskDestination !== '' && !Storage::disk('private')->exists($diskDestination)) {
            return back()->withErrors('Thư mục đích không tồn tại.');
        }

        $movedCount = 0;
        
        foreach ($paths as $sourcePath) {
            if (str_contains($sourcePath, '..')) continue;
            
            // Cannot move into itself
            if ($sourcePath === $diskDestination || str_starts_with($diskDestination, $sourcePath . '/')) {
                continue;
            }

            if (!Storage::disk('private')->exists($sourcePath)) continue;

            $itemName = basename($sourcePath);
            $newPath = $diskDestination !== '' ? $diskDestination . '/' . $itemName : $itemName;

            // Xử lý trùng tên (thêm -1, -2 etc)
            $counter = 1;
            $ext = pathinfo($itemName, PATHINFO_EXTENSION);
            $nameWithoutExt = pathinfo($itemName, PATHINFO_FILENAME);
            $baseNewPath = $newPath;
            
            while (Storage::disk('private')->exists($newPath)) {
                $newName = $nameWithoutExt . '-' . $counter . '.' . $ext;
                $newPath = $diskDestination !== '' ? $diskDestination . '/' . $newName : $newName;
                $counter++;
            }

            // Di chuyển vật lý
            Storage::disk('private')->move($sourcePath, $newPath);

            // Cập nhật DB (Document table) cho tất cả các file bị ảnh hưởng
            // Nếu là tệp tin đơn:
            Document::where('file_path', $sourcePath)->update([
                'file_path' => $newPath,
                'file_name' => basename($newPath)
            ]);

            // Nếu nó là thư mục, cập nhật toàn bộ file con nằm trong đó.
            $children = Document::where('file_path', 'like', $sourcePath . '/%')->get();
            foreach ($children as $child) {
                $relativeChildPath = substr($child->file_path, strlen($sourcePath));
                $newChildPath = $newPath . $relativeChildPath;
                $child->update([
                    'file_path' => $newChildPath
                ]);
            }

            $movedCount++;
        }

        return back()->with('success', "Đã di chuyển thành công $movedCount mục.");
    }

    public function copyItems(Request $request)
    {
        $this->authorize('create_media');

        $paths = $request->input('paths', []);
        $destination = $request->input('destination_folder', '');

        if (str_contains($destination, '..')) abort(403);
        if (empty($paths)) {
            return back()->withErrors('Không có tệp/thư mục nào được chọn để sao chép.');
        }

        $diskDestination = $destination === '/' ? '' : $destination;
        
        if ($diskDestination !== '' && !Storage::disk('private')->exists($diskDestination)) {
            return back()->withErrors('Thư mục đích không tồn tại.');
        }

        $copiedCount = 0;
        
        foreach ($paths as $sourcePath) {
            if (str_contains($sourcePath, '..')) continue;

            if (!Storage::disk('private')->exists($sourcePath)) continue;

            $itemName = basename($sourcePath);
            $newPath = $diskDestination !== '' ? $diskDestination . '/' . $itemName : $itemName;

            // Xử lý trùng tên
            $counter = 1;
            $ext = pathinfo($itemName, PATHINFO_EXTENSION);
            $nameWithoutExt = pathinfo($itemName, PATHINFO_FILENAME);
            $baseNewPath = $newPath;
            
            while (Storage::disk('private')->exists($newPath)) {
                $newName = $nameWithoutExt . ' - Copy (' . $counter . ').' . $ext;
                $newPath = $diskDestination !== '' ? $diskDestination . '/' . $newName : $newName;
                $counter++;
            }

            // Sao chép vật lý
            Storage::disk('private')->copy($sourcePath, $newPath);

            // Cập nhật DB (Document table)
            // Nếu là tệp tin:
            $doc = Document::where('file_path', $sourcePath)->first();
            if ($doc) {
                Document::create([
                    'uploaded_by' => auth()->id(),
                    'document_type' => $doc->document_type,
                    'title' => $doc->title . ' (Bản sao)',
                    'file_path' => $newPath,
                    'file_name' => basename($newPath),
                    'file_size' => $doc->file_size,
                    'mime_type' => $doc->mime_type,
                    'notes' => $doc->notes,
                ]);
            }

            $copiedCount++;
        }

        return back()->with('success', "Đã sao chép thành công $copiedCount mục.");
    }

    public function destroy(Request $request, $filename)
    {
        $this->authorize('delete_media');
        
        // Cần decode path nếu client gửi có /
        // Tuy nhiên router where('filename', '.*') dã nhận đủ.
        if (str_contains($filename, '..')) abort(403);

        $isFolder = $request->input('is_folder', false);

        if ($isFolder) {
            if (Storage::disk('private')->exists($filename)) {
                Storage::disk('private')->deleteDirectory($filename);
                // Xoá luôn các records trong DB có đường dẫn bắt đầu bằng thư mục này
                Document::where('file_path', 'like', $filename . '/%')->delete();
                return back()->with('success', 'Xóa thư mục thành công.');
            }
        } else {
            if (Storage::disk('private')->exists($filename)) {
                Storage::disk('private')->delete($filename);
                Document::where('file_path', $filename)->delete();
                return back()->with('success', 'Xóa tập tin thành công.');
            }
        }

        return back()->withErrors('Không tìm thấy mục cần xóa.');
    }

    public function serve($filename)
    {
        
        if (!Storage::disk('private')->exists($filename)) {
            abort(404);
        }

        $path = storage_path('app/private/' . $filename);
        $mimeType = Storage::mimeType('private/' . $filename);

        return Response::file($path, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . $filename . '"',
        ]);
    }

    public function showGenerateForm($filename)
    {
        $this->authorize('view_media');

        if (!Storage::disk('private')->exists($filename)) {
            abort(404);
        }

        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        if (!in_array($extension, ['doc', 'docx'])) {
            return back()->with('error', 'Chỉ hỗ trợ tạo biểu mẫu cho file Word (.doc, .docx)');
        }

        $path = storage_path('app/private/' . $filename);
        
        try {
            $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($path);
            $variables = $templateProcessor->getVariables();
            // deduplicate variables
            $variables = array_values(array_unique($variables));
        } catch (\Exception $e) {
            return back()->with('error', 'Không thể đọc file mẫu: ' . $e->getMessage());
        }

        $templateConfig = \App\Models\TemplateConfig::where('filename', $filename)->first();
        $savedConfig = $templateConfig ? $templateConfig->config : null;

        return view('admin.media.show', compact('filename', 'variables', 'savedConfig'));
    }

    public function saveConfig(Request $request, $filename)
    {
        $this->authorize('view_media');

        $config = $request->input('config', []);
        
        \App\Models\TemplateConfig::updateOrCreate(
            ['filename' => $filename],
            ['config' => $config]
        );

        return response()->json(['success' => true]);
    }

    public function generateDocument(Request $request, $filename)
    {
        $this->authorize('view_media');

        if (!Storage::disk('private')->exists($filename)) {
            abort(404);
        }

        $path = storage_path('app/private/' . $filename);
        
        try {
            $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($path);
            
            // Lấy danh sách biến gốc từ template
            $originalVars = $templateProcessor->getVariables();
            $originalVars = array_values(array_unique($originalVars));

            // Map request data back to original variable names
            // PHP/trình duyệt tự đổi khoảng trắng/dấu chấm trong thuộc tính name thành '_'
            $mappedData = [];
            foreach ($originalVars as $varName) {
                $formFieldName = str_replace([' ', '.'], '_', $varName);
                if ($request->has($formFieldName)) {
                    $mappedData[$varName] = $request->input($formFieldName);
                }
            }
            
            $tableMode = $request->input('table_mode', false);
            
            if ($tableMode) {
                $tableAnchor = $request->input('table_anchor');
                $tableCols = $request->input('table_cols', []);
                
                $rowCount = 0;
                // Find how many rows we have based on array inputs
                foreach ($tableCols as $col) {
                    $formColName = str_replace([' ', '.'], '_', $col);
                    if (is_array($request->input($formColName))) {
                        $rowCount = max($rowCount, count($request->input($formColName)));
                    }
                }

                $tableData = [];
                for ($i = 0; $i < $rowCount; $i++) {
                    $row = [];
                    foreach ($tableCols as $col) {
                        $formColName = str_replace([' ', '.'], '_', $col);
                        $colData = $request->input($formColName);
                        $row[$col] = is_array($colData) ? ($colData[$i] ?? '') : '';
                        
                        // Unset from mappedData so we don't process it below
                        if (isset($mappedData[$col])) unset($mappedData[$col]);
                    }
                    $tableData[] = $row;
                }

                if ($tableAnchor && !empty($tableData)) {
                    // Try to clone row, but if anchor is not found in table, it might throw exception.
                    $templateProcessor->cloneRowAndSetValues($tableAnchor, $tableData);
                }
            } 
            
            // Handle normal mapping with ORIGINAL variable names (preserving spaces)
            foreach ($mappedData as $key => $value) {
                if (!is_array($value)) {
                    $templateProcessor->setValue($key, $value ?? '');
                }
            }
            
            $cleanName = pathinfo($filename, PATHINFO_FILENAME);
            $outputFilename = 'Generated_' . time() . '_' . $cleanName . '.docx';
            $tempDir = storage_path('app/temp');
            $tempPath = $tempDir . '/' . $outputFilename;
            
            if (!file_exists($tempDir)) {
                mkdir($tempDir, 0755, true);
            }
            
            $templateProcessor->saveAs($tempPath);
            
            return Response::download($tempPath, $outputFilename)->deleteFileAfterSend(true);
            
        } catch (\Exception $e) {
            return back()->with('error', 'Lỗi khi tạo tài liệu: ' . $e->getMessage());
        }
    }
}

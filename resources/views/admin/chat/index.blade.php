@extends('layouts.admin')

@section('title', 'Trung tâm Trò chuyện - ProTech')

@section('content')
    <style>
        .tech-chat-container {
            height: 78vh;
            border-radius: 16px;
            box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.08);
            background: #fff;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
            height: 8px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background-color: rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background-color: rgba(0, 0, 0, 0.2);
        }

        .chat-list-item {
            cursor: pointer;
            transition: all 0.2s ease;
            border-left: 3px solid transparent;
        }

        .chat-list-item:hover {
            background-color: #f8f9fc;
            transform: translateX(3px);
        }

        .chat-list-item.active {
            background-color: #f0f3ff;
            border-left: 3px solid #4e73df;
        }

        .avatar-circle {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            flex-shrink: 0;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .msg-bubble {
            padding: 12px 18px;
            font-size: 0.95rem;
            word-break: break-word;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.04);
        }

        .msg-sent {
            background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
            color: white;
            border-radius: 18px 18px 4px 18px;
        }

        .msg-received {
            background-color: #ffffff;
            color: #333;
            border: 1px solid rgba(0, 0, 0, 0.05);
            border-radius: 18px 18px 18px 4px;
        }

        .msg-time {
            font-size: 0.70rem;
            color: #9ca3af;
            margin-top: 4px;
        }

        .hover-shadow-up {
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .hover-shadow-up:hover {
            transform: translateY(-2px);
            box-shadow: 0 0.25rem 0.75rem rgba(0, 0, 0, 0.1) !important;
        }
    </style>

    <div class="container-fluid py-2" x-data="chatApp()" x-init="init()">
        <div class="row g-0 tech-chat-container overflow-hidden">
            <!-- Conversation Sidebar -->
            <div class="col-md-4 col-lg-3 border-end d-flex flex-column h-100 bg-white" style="z-index: 10;">
                <div class="p-3 border-bottom d-flex justify-content-between align-items-center bg-white shadow-sm"
                    style="z-index: 5;">
                    <h5 class="mb-0 fw-bold d-flex align-items-center" style="color: #2c3e50;">
                        <span
                            class="bg-primary bg-opacity-10 text-primary rounded-circle d-inline-flex align-items-center justify-content-center me-2"
                            style="width: 32px; height: 32px;">
                            <i class="fab fa-facebook-messenger"></i>
                        </span>
                        Tin nhắn
                    </h5>
                    @can('chat.create')
                        <button
                            class="btn btn-primary rounded-circle shadow-sm d-flex align-items-center justify-content-center"
                            data-bs-toggle="modal" data-bs-target="#newChatModal" title="Tạo cuộc trò chuyện mới"
                            style="width: 36px; height: 36px; background: linear-gradient(135deg, #4e73df 0%, #224abe 100%); border: none;">
                            <i class="fas fa-edit"></i>
                        </button>
                    @endcan
                </div>

                <div class="overflow-auto custom-scrollbar flex-grow-1 px-2 py-2 w-100">
                    <template x-for="conv in conversations" :key="conv.id">
                        <div @click="openConversation(conv)"
                            class="d-flex align-items-center p-3 mb-2 rounded-3 chat-list-item"
                            :class="{ 'active': activeConversation && activeConversation.id === conv.id }">
                            <div class="avatar-circle me-3 flex-shrink-0"
                                :class="conv.is_group ? 'bg-info bg-gradient text-white' :
                                    'bg-secondary bg-opacity-10 text-secondary'">
                                <i class="fas" :class="conv.is_group ? 'fa-users' : 'fa-user'"></i>
                            </div>
                            <div class="flex-grow-1 overflow-hidden">
                                <h6 class="mb-1 text-truncate fw-bold" style="font-size: 0.95rem; color: #2c3e50;"
                                    x-text="getConversationName(conv)"></h6>
                                <p class="mb-0 text-muted text-truncate" style="font-size: 0.8rem;"
                                    x-text="conv.last_message ? (conv.last_message.user_id == currentUserId ? 'Bạn: ' : '') + (conv.last_message.attachment ? '[File đính kèm]' : conv.last_message.body) : 'Bắt đầu cuộc trò chuyện...'">
                                </p>
                            </div>
                        </div>
                    </template>
                    <div :class="conversations.length === 0 && !loading ? '' : 'd-none'"
                        class="text-center p-5 text-muted small d-flex flex-column align-items-center">
                        <i class="fas fa-comments fa-3x mb-3 opacity-25"></i>
                        <span class="fw-semibold">Chưa có tin nhắn nào</span>
                    </div>
                </div>
            </div>

            <!-- Chat Area -->
            <div class="col-md-8 col-lg-9 d-flex flex-column h-100 position-relative" style="background-color: #f8f9fc;">
                <template x-if="activeConversation">
                    <div class="d-flex flex-column h-100 w-100">
                        <!-- Header -->
                        <div class="p-3 border-bottom bg-white shadow-sm d-flex align-items-center w-100"
                            style="z-index: 5;">
                            <div class="avatar-circle me-3 shadow-sm" style="width: 45px; height: 45px;"
                                :class="activeConversation.is_group ? 'bg-info bg-gradient text-white' :
                                    'bg-primary bg-opacity-10 text-primary'">
                                <i class="fas fs-5" :class="activeConversation.is_group ? 'fa-users' : 'fa-user'"></i>
                            </div>
                            <div class="flex-grow-1 d-flex flex-column align-items-start">
                                <div class="d-flex align-items-center mb-1 w-100">
                                    <!-- Hiển thị tên nhóm -->
                                    <div x-show="!isEditingGroupName" class="w-100">
                                        <div class="d-flex align-items-center">
                                            <h5 class="mb-0 fw-bold me-2 text-truncate"
                                                style="color: #2c3e50; max-width: 250px;"
                                                x-text="getConversationName(activeConversation)"></h5>
                                            @can('chat.edit')
                                                <div :class="activeConversation.is_group ? '' : 'd-none'">
                                                    <button
                                                        class="btn btn-sm btn-light rounded-circle text-muted hover-shadow-up p-1 d-flex align-items-center justify-content-center flex-shrink-0"
                                                        style="width: 24px; height: 24px; border: 1px solid rgba(0,0,0,0.05);"
                                                        @click="startEditGroupName()" title="Đổi tên nhóm">
                                                        <i class="fas fa-pencil-alt" style="font-size: 0.75rem;"></i>
                                                    </button>
                                                </div>
                                            @endcan

                                        </div>
                                    </div>
                                    <!-- Form sửa tên nhóm inline -->
                                    <div x-show="isEditingGroupName" x-cloak class="w-100" x-transition>
                                        <div class="d-flex align-items-center">
                                            <input type="text" x-model="editingGroupNameValue"
                                                class="form-control form-control-sm rounded-pill me-2 shadow-sm border-primary"
                                                style="max-width: 200px; font-size: 0.9rem;" @keyup.enter="saveGroupName()"
                                                @keyup.escape="cancelEditGroupName()" x-ref="groupNameInput"
                                                placeholder="Nhập tên mới...">
                                            <button
                                                class="btn btn-sm btn-success rounded-circle p-1 d-flex align-items-center justify-content-center me-1 flex-shrink-0"
                                                style="width: 28px; height: 28px;" @click="saveGroupName()" title="Lưu">
                                                <i class="fas fa-check" style="font-size: 0.8rem;"></i>
                                            </button>
                                            <button
                                                class="btn btn-sm btn-light border rounded-circle text-danger p-1 d-flex align-items-center justify-content-center flex-shrink-0"
                                                style="width: 28px; height: 28px;" @click="cancelEditGroupName()"
                                                title="Hủy">
                                                <i class="fas fa-times" style="font-size: 0.8rem;"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <span
                                    class="badge bg-success bg-opacity-10 text-success rounded-pill px-2 border border-success border-opacity-25"
                                    style="font-size: 0.7rem; letter-spacing: 0.3px;">
                                    <i class="fas fa-circle me-1" style="font-size: 6px;"></i>Đang hoạt động
                                </span>
                            </div>
                            @can('chat.edit')
                                <div class="ms-auto" x-show="activeConversation.is_group" style="cursor: pointer;"
                                    data-bs-toggle="modal" data-bs-target="#groupMembersModal" title="Xem danh sách thành viên">
                                    <div class="bg-light rounded-circle d-flex align-items-center justify-content-center hover-shadow-up transition-all border border-primary border-opacity-10"
                                        style="width: 40px; height: 40px;">
                                        <i class="fas fa-users text-primary"></i>
                                    </div>
                                </div>
                            @endcan
                        </div>

                        <!-- Messages -->
                        <div class="flex-grow-1 p-4 bg-transparent overflow-auto custom-scrollbar w-100"
                            id="chatMessagesBox">
                            <template x-for="msg in messages" :key="msg.id">
                                <div class="d-flex mb-4"
                                    :class="msg.user_id == currentUserId ? 'justify-content-end' : 'justify-content-start'">

                                    <div x-show="msg.user_id != currentUserId" class="avatar-circle me-2 mt-auto"
                                        style="width: 32px; height: 32px; font-size: 14px; background-color: #e2e8f0; color: #64748b; box-shadow: none;">
                                        <i class="fas fa-user"></i>
                                    </div>

                                    <div class="d-flex flex-column"
                                        :class="msg.user_id == currentUserId ? 'align-items-end' : 'align-items-start'"
                                        style="max-width: 75%;">
                                        <!-- Tên người gửi trong Group Chat -->
                                        <span
                                            x-show="activeConversation.is_group && msg.user_id != currentUserId && msg.user"
                                            class="text-muted fw-bold mb-1 ms-1"
                                            style="font-size: 0.70rem; letter-spacing: 0.3px;"
                                            x-text="msg.user?.name"></span>

                                        <div class="msg-bubble"
                                            :class="msg.user_id == currentUserId ? 'msg-sent' : 'msg-received'">
                                            <span x-show="msg.body" x-text="msg.body"
                                                style="white-space: pre-wrap; display: block; line-height: 1.5;"></span>

                                            <!-- Hiển thị File / Ảnh đính kèm -->
                                            <template x-if="msg.attachment && msg.attachment.length > 0">
                                                <div class="d-flex flex-wrap gap-2" :class="msg.body ? 'mt-2' : ''">
                                                    <template x-for="(file, idx) in msg.attachment"
                                                        :key="idx">
                                                        <div class="position-relative">
                                                            <!-- Nếu là ảnh -->
                                                            <template x-if="isImage(file)">
                                                                <a :href="'/storage/' + file" target="_blank"
                                                                    class="d-block transition-all hover-shadow-up"
                                                                    style="border-radius: 8px; overflow: hidden;">
                                                                    <img loading="lazy" :src="'/storage/' + file"
                                                                        class="border"
                                                                        style="max-width: 180px; max-height: 180px; object-fit: cover; border-color: rgba(0,0,0,0.1) !important;">
                                                                </a>
                                                            </template>
                                                            <!-- Nếu không phải ảnh -->
                                                            <template x-if="!isImage(file)">
                                                                <a :href="'/storage/' + file" target="_blank"
                                                                    class="btn btn-sm btn-light border d-flex align-items-center gap-2 transition-all hover-shadow-up"
                                                                    style="text-decoration: none; border-radius: 8px; background: rgba(255,255,255,0.9);">
                                                                    <i class="fas fa-file-alt text-primary fs-5"></i>
                                                                    <span class="text-truncate text-dark fw-semibold"
                                                                        style="max-width: 140px; font-size: 0.8rem;"
                                                                        x-text="getFileName(file)"></span>
                                                                    <i class="fas fa-download text-muted ms-1"
                                                                        style="font-size: 0.7rem;"></i>
                                                                </a>
                                                            </template>
                                                        </div>
                                                    </template>
                                                </div>
                                            </template>
                                        </div>
                                        <div class="msg-time" :class="msg.user_id == currentUserId ? 'me-1' : 'ms-1'"
                                            x-text="formatTime(msg.created_at)"></div>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <!-- Input -->
                        <div class="p-3 bg-white border-top w-100" style="z-index: 5;">
                            <!-- Show selected files preview -->
                            <div x-show="files.length > 0"
                                class="d-flex flex-wrap gap-2 mb-2 p-2 bg-light border rounded-3" style="display: none;">
                                <template x-for="(file, index) in files" :key="index">
                                    <div
                                        class="d-flex align-items-center bg-white border rounded-pill px-3 py-1 shadow-sm">
                                        <i class="fas"
                                            :class="file.type.startsWith('image/') ? 'fa-image text-success' :
                                                'fa-file-alt text-primary'"></i>
                                        <span class="text-truncate ms-2 fw-semibold text-secondary"
                                            style="max-width: 120px; font-size: 0.8rem;" x-text="file.name"></span>
                                        <button type="button" class="btn btn-link text-danger p-0 ms-2"
                                            @click="removeFile(index)">
                                            <i class="fas fa-times-circle fs-6"></i>
                                        </button>
                                    </div>
                                </template>
                            </div>

                            <form @submit.prevent="sendMessage" class="d-flex align-items-center gap-2">
                                <input type="file" multiple id="chatFiles" x-ref="fileInput"
                                    @change="handleFileSelect" style="display:none">
                                <button type="button"
                                    class="btn btn-light text-secondary border rounded-circle transition-all hover-shadow-up d-flex align-items-center justify-content-center"
                                    style="width: 45px; height: 45px; flex-shrink: 0;" @click="$refs.fileInput.click()"
                                    title="Đính kèm file hoặc ảnh">
                                    <i class="fas fa-paperclip fs-5"></i>
                                </button>

                                <div class="flex-grow-1 position-relative">
                                    <input type="text" x-model="newMessage"
                                        class="form-control rounded-pill px-4 shadow-none" placeholder="Nhập tin nhắn..."
                                        style="background-color: #f0f3f8; border: 1px solid transparent; padding: 12px 20px; transition: all 0.3s;"
                                        @focus="$el.style.backgroundColor='#ffffff'; $el.style.borderColor='#4e73df'; $el.style.boxShadow='0 0 0 0.25rem rgba(78, 115, 223, 0.1)';"
                                        @blur="$el.style.backgroundColor='#f0f3f8'; $el.style.borderColor='transparent'; $el.style.boxShadow='none';">
                                </div>

                                <button type="submit"
                                    class="btn rounded-circle text-white transition-all hover-shadow-up d-flex align-items-center justify-content-center"
                                    style="width: 48px; height: 48px; background: linear-gradient(135deg, #4e73df 0%, #224abe 100%); border: none; flex-shrink: 0;"
                                    :disabled="isSending || (!newMessage.trim() && files.length === 0)">
                                    <i class="fas" :class="isSending ? 'fa-spinner fa-spin' : 'fa-paper-plane'"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </template>
                <template x-if="!activeConversation">
                    <div
                        class="h-100 d-flex flex-column align-items-center justify-content-center text-muted bg-white bg-opacity-50">
                        <div class="p-4 rounded-circle bg-white shadow mb-3 d-flex align-items-center justify-content-center"
                            style="width: 100px; height: 100px;">
                            <i class="fab fa-facebook-messenger fa-3x text-primary"
                                style="background: linear-gradient(135deg, #4e73df 0%, #224abe 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;"></i>
                        </div>
                        <h4 class="fw-bold text-dark">ProTech Chat</h4>
                        <p class="text-secondary text-center px-4" style="max-width: 400px;">Nền tảng giao tiếp nội bộ
                            nhanh chóng, an toàn và chuyên nghiệp. Khởi tạo cuộc hội thoại mới bằng cách nhấn vào nút dưới
                            đây.</p>
                        <button class="btn btn-primary rounded-pill px-4 py-2 mt-2 shadow-sm fw-bold"
                            data-bs-toggle="modal" data-bs-target="#newChatModal"
                            style="background: linear-gradient(135deg, #4e73df 0%, #224abe 100%); border: none;">
                            <i class="fas fa-plus me-2"></i>Tìm người / Nhóm chat
                        </button>
                    </div>
                </template>
            </div>
        </div>

        <!-- Modal Tin nhắn mới -->
        <div class="modal fade" id="newChatModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
                    <div class="modal-header border-bottom-0 pb-0 position-relative" style="background-color: #570ecd;">
                        <!-- Custom Nav Tabs -->
                        <ul class="nav nav-tabs border-0 w-100" role="tablist">
                            <li class="nav-item flex-fill text-center" role="presentation">
                                <button class="nav-link active fw-bold w-100 border-0 border-bottom border-3 py-3"
                                    id="tab-individual-btn" data-bs-toggle="tab" data-bs-target="#tab-individual"
                                    type="button" role="tab"
                                    @click="$el.classList.add('border-bottom', 'border-3'); $el.style.color='#ffc107'; $el.style.borderColor='#ffc107'; const otherBtn = document.getElementById('tab-group-btn'); otherBtn.classList.remove('border-bottom', 'border-3'); otherBtn.style.color='rgba(255,255,255,0.7)'; otherBtn.style.borderColor='transparent';"
                                    style="background: transparent; color: #ffc107; border-color: #ffc107 !important;">Cá
                                    nhân</button>
                            </li>
                            <li class="nav-item flex-fill text-center" role="presentation">
                                <button class="nav-link fw-bold w-100 border-0 py-3" id="tab-group-btn"
                                    data-bs-toggle="tab" data-bs-target="#tab-group" type="button" role="tab"
                                    @click="$el.classList.add('border-bottom', 'border-3'); $el.style.color='#ffc107'; $el.style.borderColor='#ffc107'; const otherBtn = document.getElementById('tab-individual-btn'); otherBtn.classList.remove('border-bottom', 'border-3'); otherBtn.style.color='rgba(255,255,255,0.7)'; otherBtn.style.borderColor='transparent';"
                                    style="background: transparent; color: rgba(255,255,255,0.7);">Tạo Nhóm</button>
                            </li>
                        </ul>
                        <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 mt-3 me-3"
                            data-bs-dismiss="modal"></button>
                    </div>
                    <!-- modal tạo chat -->
                    <div class="modal-body p-4 bg-white">
                        <div class="tab-content">
                            <!-- TAB: Chat Cá nhân -->
                            <div class="tab-pane fade show active" id="tab-individual" role="tabpanel">
                                <div class="position-relative mb-3">
                                    <i class="fas fa-search position-absolute text-muted"
                                        style="top: 50%; left: 15px; transform: translateY(-50%);"></i>
                                    <input type="text"
                                        class="form-control rounded-pill bg-light border-0 py-2 shadow-none"
                                        style="padding-left: 40px; padding-right: 15px;"
                                        placeholder="Tìm kiếm đồng nghiệp..." x-model="searchUser">
                                </div>

                                <div class="list-group list-group-flush custom-scrollbar pe-2"
                                    style="max-height: 280px; overflow-y: auto;">
                                    <template x-for="usr in filteredUsers" :key="usr.id">
                                        <button type="button"
                                            class="list-group-item list-group-item-action d-flex align-items-center border-0 rounded-3 mb-1 transition-all"
                                            style="background: transparent;"
                                            onmouseover="this.style.backgroundColor='#f0f3f8'"
                                            onmouseout="this.style.backgroundColor='transparent'"
                                            @click="startChat(usr.id)">
                                            <div class="avatar-circle bg-primary bg-gradient text-white shadow-sm me-3"
                                                style="width: 40px; height: 40px;">
                                                <i class="fas fa-user"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-0 fw-bold text-dark" style="font-size: 0.95rem;"
                                                    x-text="usr.name"></h6>
                                                <small class="text-muted" x-text="usr.email"></small>
                                            </div>
                                        </button>
                                    </template>
                                </div>
                            </div>

                            <!-- TAB: Tạo Nhóm Chat -->
                            <div class="tab-pane fade" id="tab-group" role="tabpanel">
                                <div class="mb-3">
                                    <label class="form-label fw-bold small text-primary text-uppercase mb-2">Tên
                                        nhóm</label>
                                    <input type="text"
                                        class="form-control rounded-pill bg-light border-0 px-4 py-2 shadow-none"
                                        placeholder="VD: Nhóm Marketing, Dự án A..." x-model="groupName">
                                </div>
                                <div class="mb-4">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <label class="form-label fw-bold small text-primary text-uppercase mb-0">Thành
                                            viên</label>
                                        <span class="badge bg-primary rounded-pill" x-show="selectedGroupUsers.length > 0"
                                            x-text="selectedGroupUsers.length + ' người'"></span>
                                    </div>
                                    <div class="position-relative mb-2">
                                        <i class="fas fa-filter position-absolute text-muted"
                                            style="top: 50%; left: 15px; transform: translateY(-50%); font-size: 0.8rem;"></i>
                                        <input type="text"
                                            class="form-control rounded-pill bg-light border-0 py-2 shadow-none"
                                            style="padding-left: 35px; padding-right: 15px; font-size: 0.85rem;"
                                            placeholder="Lọc để thêm..." x-model="searchUserGroup">
                                    </div>
                                    <div class="list-group list-group-flush custom-scrollbar border rounded-3 p-1"
                                        style="height: 180px; overflow-y: auto; background: #fafafa;">
                                        <template x-for="usr in filteredGroupUsers" :key="usr.id">
                                            <label
                                                class="list-group-item d-flex align-items-center border-0 rounded-2 mb-1 transition-all"
                                                style="cursor: pointer; background: transparent;"
                                                onmouseover="this.style.backgroundColor='#eef2f7'"
                                                onmouseout="this.style.backgroundColor='transparent'">
                                                <input class="form-check-input me-3 mt-0 flex-shrink-0" type="checkbox"
                                                    :value="usr.id" x-model="selectedGroupUsers"
                                                    style="width: 1.2rem; height: 1.2rem; cursor: pointer;">
                                                <div class="avatar-circle bg-secondary bg-opacity-25 text-secondary flex-shrink-0 me-3"
                                                    style="width: 32px; height: 32px; font-size: 0.8rem; box-shadow: none;">
                                                    <i class="fas fa-user"></i>
                                                </div>
                                                <div class="flex-grow-1 overflow-hidden">
                                                    <h6 class="mb-0 fw-semibold text-truncate text-dark"
                                                        style="font-size: 0.9rem;" x-text="usr.name"></h6>
                                                </div>
                                            </label>
                                        </template>
                                    </div>
                                </div>

                                <button type="button"
                                    class="btn btn-primary w-100 rounded-pill fw-bold shadow-sm py-2 d-flex justify-content-center align-items-center"
                                    style="background: linear-gradient(135deg, #4e73df 0%, #224abe 100%); border: none;"
                                    @click="createGroupChat"
                                    :disabled="!groupName || selectedGroupUsers.length < 1 || isCreatingGroup">
                                    <i class="fas me-2" :class="isCreatingGroup ? 'fa-spinner fa-spin' : 'fa-users'"></i>
                                    Tạo Nhóm Ngay
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Danh sách thành viên -->
        <div class="modal fade" id="groupMembersModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered"> <!-- Removed modal-sm -->
                <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
                    <div class="modal-header border-bottom-0 pb-0 position-relative d-flex justify-content-between align-items-center"
                        style="background-color: #570ecd;">
                        <div class="d-flex align-items-center py-3 ms-3">
                            <h5 class="modal-title fw-bold m-0" style="color: #ffc107;">Thành viên nhóm</h5>
                            <button
                                class="btn btn-sm btn-light text-primary rounded-circle shadow-sm ms-3 d-flex justify-content-center align-items-center"
                                @click="showAddMemberForm = !showAddMemberForm; searchAddMember = '';"
                                title="Thêm thành viên" style="width: 32px; height: 32px;">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            style="position: absolute; right: 15px; top: 22px;"></button>
                    </div>

                    <!-- Search & Add Member Section -->
                    <div class="bg-light px-4 pb-3 border-bottom position-relative" style="z-index: 1050; display: none;"
                        x-show="showAddMemberForm" x-transition>
                        <div class="position-relative mt-2">
                            <i class="fas fa-search position-absolute text-muted"
                                style="top: 50%; left: 15px; transform: translateY(-50%); z-index: 1052;"></i>
                            <input type="text"
                                class="form-control rounded-pill shadow-sm border-0 py-2 position-relative"
                                style="padding-left: 40px; padding-right: 15px; z-index: 1051;"
                                placeholder="Tìm kiếm nhân viên để thêm..." x-model="searchAddMember">

                            <!-- Bảng overlay Gợi ý nhân viên -->
                            <div class="list-group list-group-flush custom-scrollbar shadow-lg rounded-3 bg-white position-absolute w-100"
                                style="max-height: 260px; overflow-y: auto; top: 100%; left: 0; margin-top: 5px; z-index: 1050; border: 1px solid rgba(0,0,0,0.08);">
                                <template x-for="usr in filteredAddMembers" :key="usr.id">
                                    <button type="button"
                                        class="list-group-item list-group-item-action d-flex align-items-center border-0 py-2 transition-all"
                                        @click="newMemberId = usr.id; addGroupMember()" :disabled="isManagingMember">
                                        <div class="avatar-circle bg-primary bg-opacity-10 text-primary flex-shrink-0 me-3"
                                            style="width: 32px; height: 32px; font-size: 0.8rem; box-shadow: none;">
                                            <i class="fas fa-user"></i>
                                        </div>
                                        <div class="flex-grow-1 text-start">
                                            <h6 class="mb-0 fw-semibold text-dark" style="font-size: 0.9rem;"
                                                x-text="usr.name"></h6>
                                        </div>
                                        <i class="fas"
                                            :class="isManagingMember && newMemberId == usr.id ?
                                                'fa-spinner fa-spin text-primary' : 'fa-plus text-success'"></i>
                                    </button>
                                </template>
                                <div class="p-3 text-center text-muted small" x-show="filteredAddMembers.length === 0">
                                    Không tìm thấy nhân viên phù hợp
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-body p-0 custom-scrollbar" style="max-height: 400px; overflow-y: auto;">
                        <div class="list-group list-group-flush" x-show="activeConversation && activeConversation.users">
                            <template x-for="usr in activeConversation?.users" :key="usr.id">
                                <div
                                    class="list-group-item d-flex align-items-center border-0 py-3 px-4 hover-shadow-up transition-all">
                                    <div class="avatar-circle bg-secondary bg-opacity-25 text-secondary flex-shrink-0 me-3"
                                        style="width: 40px; height: 40px; font-size: 1rem; box-shadow: none;">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-0 fw-bold text-dark" style="font-size: 0.95rem;" x-text="usr.name">
                                        </h6>
                                        <small class="text-muted" x-show="usr.email" x-text="usr.email"></small>
                                    </div>
                                    <button
                                        class="btn btn-sm btn-light text-danger rounded-circle p-2 ms-2 transition-all hover-shadow-up d-flex align-items-center justify-content-center"
                                        title="Xóa thành viên" @click="removeGroupMember(usr.id)"
                                        x-show="usr.id != currentUserId" style="width: 35px; height: 35px;">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- Footer for Disband Group Button -->
                    <div class="modal-footer bg-light border-top-0 d-flex justify-content-center">
                        <button type="button"
                            class="btn btn-outline-danger w-100 rounded-pill fw-bold shadow-sm py-2 d-flex justify-content-center align-items-center transition-all"
                            @click="disbandGroup" x-show="activeConversation && activeConversation.is_group"
                            :disabled="isDisbandingGroup">
                            <i class="fas me-2" :class="isDisbandingGroup ? 'fa-spinner fa-spin' : 'fa-trash-alt'"></i>
                            Giải tán nhóm
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    {{-- Pusher JS CDN (Reverb dùng giao thức Pusher, không cần Laravel Echo) --}}
    <script src="https://js.pusher.com/8.4.0/pusher.min.js"></script>

    <script>
        // Kết nối thông qua dịch vụ Pusher Cloud thay cho Reverb
        const pusherKey =
            '{{ config('broadcasting.connections.pusher.key', env('PUSHER_APP_KEY', '3f5e901ef78ec4565929')) }}';
        const pusherCluster =
            '{{ config('broadcasting.connections.pusher.options.cluster', env('PUSHER_APP_CLUSTER', 'ap1')) }}';

        const reverbPusher = new Pusher(pusherKey, {
            cluster: pusherCluster,
            forceTLS: true,
            authEndpoint: '/broadcasting/auth',
            auth: {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            }
        });

        function chatApp() {
            return {
                currentUserId: {{ auth()->id() }},
                conversations: [],
                messages: [],
                users: [],
                searchUser: '',
                searchUserGroup: '',
                groupName: '',
                selectedGroupUsers: [],
                isCreatingGroup: false,
                activeConversation: null,
                newMessage: '',
                files: [],
                isSending: false,
                loading: true,
                showAddMemberForm: false,
                searchAddMember: '',
                newMemberId: '',
                isManagingMember: false,
                isDisbandingGroup: false,
                isEditingGroupName: false,
                editingGroupNameValue: '',
                pusherChannel: null, // Pusher channel instance

                init() {
                    this.fetchConversations();
                    this.fetchUsers();
                },

                get filteredUsers() {
                    if (this.searchUser === '') return this.users;
                    return this.users.filter(u =>
                        u.name.toLowerCase().includes(this.searchUser.toLowerCase())
                    );
                },

                get filteredGroupUsers() {
                    if (this.searchUserGroup === '') return this.users;
                    return this.users.filter(u =>
                        u.name.toLowerCase().includes(this.searchUserGroup.toLowerCase())
                    );
                },

                get filteredAddMembers() {
                    if (this.searchAddMember.trim() === '') return this.availableUsersForGroup;
                    return this.availableUsersForGroup.filter(u =>
                        u.name.toLowerCase().includes(this.searchAddMember.toLowerCase())
                    );
                },

                get availableUsersForGroup() {
                    if (!this.activeConversation || !this.activeConversation.is_group) return [];
                    const currentMemberIds = this.activeConversation.users.map(u => u.id);
                    return this.users.filter(u => !currentMemberIds.includes(u.id));
                },

                addGroupMember() {
                    if (!this.newMemberId || !this.activeConversation) return;
                    this.isManagingMember = true;

                    fetch(`/admin/chat/conversations/${this.activeConversation.id}/add-member`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                user_id: this.newMemberId,
                                _token: '{{ csrf_token() }}'
                            })
                        })
                        .then(r => r.json())
                        .then(data => {
                            if (!data.error) {
                                this.activeConversation = data;
                                const idx = this.conversations.findIndex(c => c.id === data.id);
                                if (idx !== -1) this.conversations[idx] = data;
                                this.newMemberId = '';
                                this.showAddMemberForm = false;
                            }
                        })
                        .finally(() => {
                            this.isManagingMember = false;
                        });
                },

                removeGroupMember(userId) {
                    if (!this.activeConversation) return;

                    Swal.fire({
                        title: 'Xác nhận xóa',
                        text: 'Bạn có chắc chắn muốn xóa thành viên này khỏi nhóm?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Đồng ý',
                        cancelButtonText: 'Hủy'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            this.isManagingMember = true;

                            fetch(`/admin/chat/conversations/${this.activeConversation.id}/remove-member`, {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'Accept': 'application/json'
                                    },
                                    body: JSON.stringify({
                                        user_id: userId,
                                        _token: '{{ csrf_token() }}'
                                    })
                                })
                                .then(r => r.json())
                                .then(data => {
                                    if (!data.error) {
                                        this.activeConversation = data;
                                        const idx = this.conversations.findIndex(c => c.id === data.id);
                                        if (idx !== -1) this.conversations[idx] = data;
                                    } else {
                                        Swal.fire('Lỗi', data.error || 'Không thể xóa thành viên.', 'error');
                                    }
                                })
                                .finally(() => {
                                    this.isManagingMember = false;
                                });
                        }
                    });
                },

                startEditGroupName() {
                    if (!this.activeConversation || !this.activeConversation.is_group) return;
                    this.editingGroupNameValue = this.activeConversation.name || '';
                    this.isEditingGroupName = true;
                    // Auto-focus the input after Alpine renders it
                    setTimeout(() => {
                        if (this.$refs.groupNameInput) {
                            this.$refs.groupNameInput.focus();
                        }
                    }, 50);
                },

                cancelEditGroupName() {
                    this.isEditingGroupName = false;
                    this.editingGroupNameValue = '';
                },

                saveGroupName() {
                    if (!this.activeConversation || !this.activeConversation.is_group) return;

                    const newName = this.editingGroupNameValue;
                    const currentName = this.activeConversation.name || '';

                    if (!newName || newName.trim() === '') {
                        Swal.fire('Thông báo', 'Tên nhóm không được để trống.', 'warning');
                        return;
                    }

                    if (newName.trim() === currentName) {
                        this.cancelEditGroupName();
                        return;
                    }

                    fetch(`/admin/chat/conversations/${this.activeConversation.id}/name`, {
                            method: 'PATCH',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                name: newName.trim(),
                                _token: '{{ csrf_token() }}'
                            })
                        })
                        .then(r => r.json())
                        .then(data => {
                            if (!data.error) {
                                this.activeConversation = data;
                                const idx = this.conversations.findIndex(c => c.id === data.id);
                                if (idx !== -1) this.conversations[idx] = data;
                                this.isEditingGroupName = false;
                            } else {
                                Swal.fire('Lỗi', data.error || 'Có lỗi xảy ra khi đổi tên nhóm.', 'error');
                            }
                        })
                        .catch(e => {
                            console.error('Lỗi khi đổi tên nhóm:', e);
                            Swal.fire('Lỗi', 'Có lỗi xảy ra, vui lòng thử lại sau.', 'error');
                        });
                },

                disbandGroup() {
                    if (!this.activeConversation || !this.activeConversation.is_group) return;

                    Swal.fire({
                        title: 'Giải tán nhóm?',
                        text: 'Bạn có chắc chắn muốn giải tán nhóm này? Tất cả dữ liệu tin nhắn sẽ bị xóa vĩnh viễn.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Có, giải tán',
                        cancelButtonText: 'Hủy',
                        confirmButtonColor: '#dc3545'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            this.isDisbandingGroup = true;

                            fetch(`/admin/chat/conversations/${this.activeConversation.id}/group`, {
                                    method: 'DELETE',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'Accept': 'application/json'
                                    },
                                    body: JSON.stringify({
                                        _token: '{{ csrf_token() }}'
                                    })
                                })
                                .then(r => r.json())
                                .then(data => {
                                    if (data.success) {
                                        this.conversations = this.conversations.filter(c => c.id !== this
                                            .activeConversation.id);
                                        this.activeConversation = null;
                                        bootstrap.Modal.getInstance(document.getElementById(
                                            'groupMembersModal'))?.hide();
                                        Swal.fire('Thành công', 'Đã giải tán nhóm.', 'success');
                                    } else {
                                        Swal.fire('Lỗi', data.error || 'Có lỗi xảy ra khi giải tán nhóm.',
                                            'error');
                                    }
                                })
                                .catch(e => {
                                    console.error('Lỗi khi giải tán nhóm:', e);
                                    Swal.fire('Lỗi', 'Có lỗi xảy ra, vui lòng thử lại sau.', 'error');
                                })
                                .finally(() => {
                                    this.isDisbandingGroup = false;
                                });
                        }
                    });
                },

                createGroupChat() {
                    if (!this.groupName || this.selectedGroupUsers.length < 1) return;
                    this.isCreatingGroup = true;

                    fetch('/admin/chat/conversations/group', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                name: this.groupName,
                                user_ids: this.selectedGroupUsers,
                                _token: '{{ csrf_token() }}'
                            })
                        })
                        .then(r => r.json())
                        .then(data => {
                            bootstrap.Modal.getInstance(document.getElementById('newChatModal'))?.hide();
                            if (!this.conversations.find(c => c.id === data.id)) {
                                this.conversations.unshift(data);
                            }
                            // Reset form
                            this.groupName = '';
                            this.selectedGroupUsers = [];
                            this.searchUserGroup = '';

                            this.openConversation(data);
                        })
                        .finally(() => {
                            this.isCreatingGroup = false;
                        });
                },

                fetchConversations() {
                    this.loading = true;
                    fetch('/admin/chat/conversations')
                        .then(r => r.json())
                        .then(data => {
                            this.conversations = data;
                            this.loading = false;
                        });
                },

                fetchUsers() {
                    fetch('/admin/chat/users')
                        .then(r => r.json())
                        .then(data => {
                            this.users = data;
                        });
                },

                getConversationName(conv) {
                    if (conv.is_group) return conv.name || 'Nhóm chat';
                    const other = conv.users.find(u => u.id != this.currentUserId);
                    return other ? other.name : 'Người dùng Ẩn';
                },

                openConversation(conv) {
                    // Unsubscribe kênh cũ
                    if (this.pusherChannel) {
                        reverbPusher.unsubscribe(this.pusherChannel.name);
                        this.pusherChannel = null;
                    }

                    this.activeConversation = conv;
                    this.messages = [];
                    this.files = [];
                    this.fetchMessages();

                    // Subscribe private channel của cuộc trò chuyện với Pusher.js
                    const self = this;
                    this.pusherChannel = reverbPusher.subscribe('private-conversation.' + conv.id);
                    this.pusherChannel.bind('message.sent', function(msg) {
                        if (!self.messages.some(m => m.id === msg.id)) {
                            self.messages.push(msg);
                            self.scrollToBottom();
                            const idx = self.conversations.findIndex(c => c.id === msg.conversation_id);
                            if (idx !== -1) self.conversations[idx].last_message = msg;
                        }
                    });
                },

                fetchMessages() {
                    if (!this.activeConversation) return;
                    fetch('/admin/chat/conversations/' + this.activeConversation.id + '/messages')
                        .then(r => r.json())
                        .then(data => {
                            this.messages = data;
                            this.scrollToBottom();
                        });
                },

                handleFileSelect(event) {
                    const newFiles = Array.from(event.target.files);
                    this.files = [...this.files, ...newFiles];
                    event.target.value = ''; // reset input
                },

                removeFile(index) {
                    this.files.splice(index, 1);
                },

                isImage(path) {
                    if (!path) return false;
                    return path.match(/\.(jpeg|jpg|gif|png|webp|svg)$/i) != null;
                },

                getFileName(path) {
                    if (!path) return '';
                    const parts = path.split('/');
                    return parts[parts.length - 1];
                },

                sendMessage() {
                    if (!this.newMessage.trim() && this.files.length === 0) return;
                    if (!this.activeConversation || this.isSending) return;

                    this.isSending = true;

                    // Optimistic UI for TEXT only messages
                    const hasFiles = this.files.length > 0;
                    const tempId = 'temp_' + Date.now();
                    const bodyToSend = this.newMessage;

                    if (!hasFiles && bodyToSend.trim()) {
                        this.messages.push({
                            id: tempId,
                            user_id: this.currentUserId,
                            body: bodyToSend,
                            attachment: [],
                            created_at: new Date().toISOString()
                        });
                        this.newMessage = '';
                        this.scrollToBottom();
                    }

                    // Prepare FormData
                    const formData = new FormData();
                    formData.append('body', bodyToSend);
                    formData.append('_token', '{{ csrf_token() }}');
                    this.files.forEach((file, idx) => {
                        formData.append(`attachments[${idx}]`, file);
                    });

                    // We already saved values we need to send, can clear inputs so user doesn't wait
                    if (hasFiles) {
                        this.newMessage = '';
                        this.files = [];
                    }

                    fetch('/admin/chat/conversations/' + this.activeConversation.id + '/messages', {
                            method: 'POST',
                            headers: {
                                'Accept': 'application/json',
                                'X-Socket-Id': reverbPusher.connection.socket_id ?? ''
                            },
                            body: formData
                        })
                        .then(r => r.json())
                        .then(data => {
                            if (!hasFiles) {
                                // Replace optimistic message
                                const idx = this.messages.findIndex(m => m.id === tempId);
                                if (idx !== -1) this.messages[idx] = data;
                            } else {
                                // With files, we didn't optimistic update, so just push real message
                                if (data && data.id) {
                                    this.messages.push(data);
                                    this.scrollToBottom();
                                }
                            }
                            const cIdx = this.conversations.findIndex(c => c.id === data.conversation_id);
                            if (cIdx !== -1) this.conversations[cIdx].last_message = data;
                        })
                        .finally(() => {
                            this.isSending = false;
                        });
                },

                startChat(userId) {
                    fetch('/admin/chat/conversations/start', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                user_id: userId,
                                _token: '{{ csrf_token() }}'
                            })
                        })
                        .then(r => r.json())
                        .then(data => {
                            bootstrap.Modal.getInstance(document.getElementById('newChatModal'))?.hide();
                            if (!this.conversations.find(c => c.id === data.id)) {
                                this.conversations.unshift(data);
                            }
                            this.openConversation(data);
                        });
                },

                scrollToBottom() {
                    setTimeout(() => {
                        const box = document.getElementById('chatMessagesBox');
                        if (box) box.scrollTop = box.scrollHeight;
                    }, 80);
                },

                formatTime(iso) {
                    if (!iso) return '';
                    return new Date(iso).toLocaleTimeString('vi-VN', {
                        hour: '2-digit',
                        minute: '2-digit',
                        hour12: false
                    });
                }
            }
        }
    </script>
@endsection

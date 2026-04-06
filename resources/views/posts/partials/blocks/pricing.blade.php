<div class="{{ !empty($content['full_width']) ? 'container-fluid px-0' : 'container' }} px-0">
    <div class="row g-4 mx-0">
        @if(!empty($content['plans']))
            @foreach($content['plans'] as $plan)
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm rounded-5 text-center p-4 p-lg-5 hover-shadow-lg transition-all border-top border-5 border-primary" style="overflow: visible;">
                        <h4 class="fw-bold mb-3 text-dark text-uppercase small">{{ $plan['name'] ?? 'Cơ bản' }}</h4>
                        <div class="display-5 fw-bold text-primary mb-3">{{ $plan['price'] ?? '0đ' }}</div>
                        <p class="text-muted small mb-4 opacity-75">Gói lựa chọn hoàn hảo</p>
                        
                        <ul class="list-unstyled mb-5 text-start d-inline-block mx-auto">
                            @if(!empty($plan['features']))
                                @foreach(explode("\n", $plan['features']) as $feat)
                                    @if(trim($feat))
                                        <li class="mb-3 d-flex align-items-center gap-2">
                                            <i class="fas fa-check-circle text-primary opacity-50"></i>
                                            <span>{{ trim($feat) }}</span>
                                        </li>
                                    @endif
                                @endforeach
                            @endif
                        </ul>

                        <div class="mt-auto">
                            <a href="#" class="btn btn-primary w-100 py-3 rounded-pill fw-bold">{{ $plan['button_label'] ?? 'Chọn gói' }}</a>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</div>

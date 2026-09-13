@extends('frontend.layouts.master')
@section('title','Web bán tai nghe || Bắt Sóng 45s')
@section('main-content')

<section class="headphone-game-hero">
    <div class="container">
        <div class="headphone-game-hero-card">
            <div class="row align-items-center">
                <div class="col-lg-8 col-12">
                    <span class="headphone-game-kicker">Bắt Sóng 45s</span>
                    <h1>Chơi một minigame thật, rồi để hệ thống đoán gu tai nghe của bạn.</h1>
                    <p>Di chuyển sang trái phải để bắt item hợp gu, né tạp âm và hoàn thành trong 30 giây. Kết quả sẽ mở ra 3 mẫu tai nghe phù hợp nhất kèm bài viết liên quan.</p>
                    <div class="headphone-game-hero-actions">
                        <a href="#headphone-game-shell" class="btn headphone-game-primary-btn">Chơi ngay</a>
                        <a href="{{route('product-grids')}}" class="btn headphone-game-ghost-btn">Xem tất cả sản phẩm</a>
                    </div>
                </div>
                <div class="col-lg-4 col-12">
                    <div class="headphone-game-hero-meter">
                        <div class="headphone-game-meter-ring">
                            <strong>30s</strong>
                            <span>Có âm thanh</span>
                        </div>
                        <ul class="headphone-game-meter-list">
                            <li>Minigame thao tác thật, bắt item theo đúng gu nghe của bạn</li>
                            <li>Âm thanh tương tác nhẹ, có thể bật hoặc tắt bất cứ lúc nào</li>
                            <li>Hoàn thành game rồi mới mở gợi ý mua hàng phù hợp</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section headphone-game-shell" id="headphone-game-shell">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-12">
                <div class="headphone-game-card" data-game-card>
                    <div class="headphone-game-hud">
                        <div class="headphone-game-stat">
                            <span>Thời gian</span>
                            <strong data-game-timer>30s</strong>
                        </div>
                        <div class="headphone-game-stat">
                            <span>Điểm</span>
                            <strong data-game-score>0</strong>
                        </div>
                        <div class="headphone-game-stat">
                            <span>Chặng</span>
                            <strong data-game-wave>Bối cảnh</strong>
                        </div>
                        <button type="button" class="headphone-game-sound-btn" data-sound-toggle aria-pressed="true">
                            Âm thanh: Bật
                        </button>
                    </div>

                    <div class="headphone-game-live-tags">
                        <span data-game-tag="office">Làm việc: 0</span>
                        <span data-game-tag="gaming">Gaming: 0</span>
                        <span data-game-tag="running">Di chuyển: 0</span>
                        <span data-game-tag="study">Êm lâu: 0</span>
                        <span data-game-tag="focus">Tập trung: 0</span>
                        <span data-game-tag="value">Giá ngon: 0</span>
                    </div>

                    <div class="headphone-game-stage" data-game-stage tabindex="0" aria-label="Sân chơi Bắt Sóng 45 giây">
                        <div class="headphone-game-scene-props" aria-hidden="true">
                            <span class="headphone-game-scene-prop headphone-game-scene-prop--warm"></span>
                            <span class="headphone-game-scene-prop headphone-game-scene-prop--cup"></span>
                            <span class="headphone-game-scene-prop headphone-game-scene-prop--steam headphone-game-scene-prop--steam-a"></span>
                            <span class="headphone-game-scene-prop headphone-game-scene-prop--steam headphone-game-scene-prop--steam-b"></span>
                            <span class="headphone-game-scene-prop headphone-game-scene-prop--streak headphone-game-scene-prop--streak-a"></span>
                            <span class="headphone-game-scene-prop headphone-game-scene-prop--streak headphone-game-scene-prop--streak-b"></span>
                            <span class="headphone-game-scene-prop headphone-game-scene-prop--streak headphone-game-scene-prop--streak-c"></span>
                            <span class="headphone-game-scene-prop headphone-game-scene-prop--rgb headphone-game-scene-prop--rgb-a"></span>
                            <span class="headphone-game-scene-prop headphone-game-scene-prop--rgb headphone-game-scene-prop--rgb-b"></span>
                            <span class="headphone-game-scene-prop headphone-game-scene-prop--rgb headphone-game-scene-prop--rgb-c"></span>
                            <span class="headphone-game-scene-prop headphone-game-scene-prop--timer">04:00</span>
                            <span class="headphone-game-scene-prop headphone-game-scene-prop--notebook"></span>
                            <span class="headphone-game-scene-prop headphone-game-scene-prop--pen"></span>
                        </div>

                        <div class="headphone-game-player" data-game-player>
                            <span class="headphone-game-player__halo"></span>
                            <span class="headphone-game-player__icon">
                                HD
                            </span>
                        </div>

                        <div class="headphone-game-overlay is-active" data-game-start-screen>
                            <span class="headphone-game-kicker">Chặng 1: Bối cảnh</span>
                            <h2 data-game-start-title>Chọn scene để dựng sân chơi.</h2>
                            <p data-game-start-copy>Chọn một bối cảnh trước khi bắt đầu. Nền game, wave mở màn và hồ sơ gợi ý sẽ đổi theo scene bạn chọn.</p>
                            <div class="headphone-game-scene-grid" data-scene-grid>
                                <button type="button" class="headphone-game-scene-btn" data-scene-choice="office">
                                    <strong>Cafe tập trung</strong>
                                    <span>Ấm nhẹ, tập trung, mic rõ.</span>
                                </button>
                                <button type="button" class="headphone-game-scene-btn" data-scene-choice="running">
                                    <strong>Commute</strong>
                                    <span>Di chuyển gọn, mở nhạc thật nhanh.</span>
                                </button>
                                <button type="button" class="headphone-game-scene-btn" data-scene-choice="gaming">
                                    <strong>Gaming đêm</strong>
                                    <span>Desk tối, callout rõ, vào trận gắt.</span>
                                </button>
                                <button type="button" class="headphone-game-scene-btn" data-scene-choice="study">
                                    <strong>Học 4 tiếng</strong>
                                    <span>Êm tai, bền nhịp, đeo lâu không mệt.</span>
                                </button>
                            </div>
                            <div class="headphone-game-start-meta" data-game-scene-meta>Scene sẽ khóa cho Chặng 1 và đổi hẳn cảnh nền của game.</div>
                            <button type="button" class="btn headphone-game-primary-btn" data-game-start disabled>Chọn bối cảnh để bắt đầu</button>
                        </div>

                        <div class="headphone-game-overlay" data-game-end-screen>
                            <span class="headphone-game-kicker">Kết thúc game</span>
                            <h2 data-end-title>Profile của bạn đã hiện ra.</h2>
                            <p data-end-copy></p>
                            <div class="headphone-game-result-tags" data-end-tags></div>
                            <div class="headphone-game-end-actions">
                                <button type="button" class="btn headphone-game-primary-btn" data-submit-recommendation>Xem gợi ý ngay</button>
                                <button type="button" class="btn headphone-game-ghost-btn" data-game-restart>Chơi lại</button>
                            </div>
                        </div>
                    </div>

                    <div class="headphone-game-mobile-controls">
                        <button type="button" class="headphone-game-control-btn" data-control="left">Giữ trái</button>
                        <button type="button" class="headphone-game-control-btn" data-control="right">Giữ phải</button>
                    </div>

                    <form action="{{route('headphone-quiz.submit')}}" method="POST" id="headphone-game-form" class="headphone-game-hidden-form">
                        @csrf
                        <input type="hidden" name="experience_mode" value="arcade">
                        <input type="hidden" name="primary_use" data-hidden-answer="primary_use">
                        <input type="hidden" name="form_factor" data-hidden-answer="form_factor">
                        <input type="hidden" name="priority" data-hidden-answer="priority">
                        <input type="hidden" name="budget" data-hidden-answer="budget">
                    </form>
                </div>
            </div>

            <div class="col-lg-4 col-12">
                <div class="headphone-game-side-card">
                    <span class="headphone-game-kicker">Game này đọc gì?</span>
                    <h3>Không bắt bạn tick chọn từng ô, mà đọc gu qua cách bạn phản xạ với item.</h3>
                    <div class="headphone-game-side-points">
                        <article>
                            <strong>Chặng 1: Bối cảnh</strong>
                            <p>Bạn chọn cafe, commute, gaming đêm hay học 4 tiếng trước. Scene này sẽ đổi nền game và khóa mood cho wave mở màn.</p>
                        </article>
                        <article>
                            <strong>Chặng 2: Ưu tiên</strong>
                            <p>Tập trung, mic, êm tai hay giá ngon. Đây là phần tách rõ chất gu nghe của bạn.</p>
                        </article>
                        <article>
                            <strong>Chặng 3: Kiểu đeo & ngân sách</strong>
                            <p>Game sẽ đọc thêm xu hướng over-ear, true wireless và tầm giá bạn sẵn sàng xuống tiền.</p>
                        </article>
                    </div>

                    <div class="headphone-game-side-note">
                        <span>Âm thanh trong game</span>
                        <ul>
                            <li>Bắt item đúng có tiếng sáng nhẹ</li>
                            <li>Ăn phải tạp âm có tiếng cảnh báo</li>
                            <li>Có nút tắt âm thanh ngay trong HUD</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@if(!empty($recommendation))
    <section class="section headphone-game-result" id="quiz-result">
        <div class="container">
            <div class="headphone-game-result-intro">
                <span class="headphone-game-kicker">{{$recommendation['experience_label'] ?? 'Kết quả gợi ý'}}</span>
                <h2>{{$recommendation['profile_title']}}</h2>
                <p>{{$recommendation['profile_copy']}}</p>
                <div class="headphone-game-chip-group">
                    @foreach($recommendation['selected_labels'] as $label)
                        <span>{{$label}}</span>
                    @endforeach
                </div>
            </div>

            <div class="row">
                @foreach($recommendation['products'] as $item)
                    @php
                        $photo = explode(',', $item['product']->photo);
                        $originalPrice = $item['product']->price;
                        $listingMedia = $item['product']->listing_media ?? [
                            'display_url' => $photo[0] ?? '',
                            'display_width' => null,
                            'is_low_resolution' => false,
                        ];
                        $imageShellStyle = "--headphone-game-product-image:url('" . ($listingMedia['display_url'] ?? '') . "');";
                        if(!empty($listingMedia['display_width'])){
                            $imageShellStyle .= '--headphone-game-product-image-width:' . $listingMedia['display_width'] . 'px;';
                        }
                    @endphp
                    <div class="col-lg-4 col-md-6 col-12">
                        <article class="headphone-game-product-card">
                            <div class="headphone-game-product-media{{ !empty($listingMedia['is_low_resolution']) ? ' headphone-game-product-media--low-res' : '' }}">
                                <div class="headphone-game-product-media__shell" style="{{ $imageShellStyle }}">
                                    <img class="headphone-game-product-media__image{{ !empty($listingMedia['is_low_resolution']) ? ' headphone-game-product-media__image--enhanced' : '' }}" src="{{$listingMedia['display_url']}}" alt="{{$item['product']->title}}">
                                </div>
                                <span>{{$item['fit_label']}}</span>
                            </div>
                            <div class="headphone-game-product-body">
                                <p class="headphone-game-product-meta">
                                    @if(optional($item['product']->brand)->title)
                                        {{optional($item['product']->brand)->title}}
                                    @else
                                        Gợi ý âm thanh
                                    @endif
                                </p>
                                <h3><a href="{{route('product-detail',$item['product']->slug)}}">{{$item['product']->title}}</a></h3>
                                <div class="headphone-game-product-price">
                                    <strong>{{number_format($item['final_price'],0)}} đ</strong>
                                    @if((float) $item['product']->discount > 0)
                                        <del>{{number_format($originalPrice,0)}} đ</del>
                                    @endif
                                </div>
                                <ul class="headphone-game-reason-list">
                                    @foreach($item['reasons'] as $reason)
                                        <li>{{$reason}}</li>
                                    @endforeach
                                </ul>
                                <div class="headphone-game-product-actions">
                                    <a href="{{route('product-detail',$item['product']->slug)}}" class="btn headphone-game-primary-btn">Xem chi tiết</a>
                                    @if(optional($item['product']->brand)->slug)
                                        <a href="{{route('product-brand', optional($item['product']->brand)->slug)}}" class="btn headphone-game-ghost-btn">Thêm mẫu cùng hãng</a>
                                    @else
                                        <a href="{{route('product-grids')}}" class="btn headphone-game-ghost-btn">Xem thêm sản phẩm</a>
                                    @endif
                                </div>
                            </div>
                        </article>
                    </div>
                @endforeach
            </div>

            @if(!empty($recommendation['post']))
                <div class="headphone-game-reading-card">
                    <div class="row align-items-center">
                        <div class="col-lg-5 col-12">
                            <div class="headphone-game-reading-media">
                                <img src="{{$recommendation['post']['post']->photo}}" alt="{{$recommendation['post']['post']->title}}">
                            </div>
                        </div>
                        <div class="col-lg-7 col-12">
                            <div class="headphone-game-reading-body">
                                <span class="headphone-game-kicker">Đọc thêm 2 phút</span>
                                <h3>{{$recommendation['post']['post']->title}}</h3>
                                <p class="headphone-game-reading-reason">{{$recommendation['post']['reason']}}</p>
                                <p>{{\Illuminate\Support\Str::limit(strip_tags(html_entity_decode((string) $recommendation['post']['post']->summary)), 150)}}</p>
                                <a href="{{route('blog.detail',$recommendation['post']['post']->slug)}}" class="btn headphone-game-primary-btn">Mở bài viết này</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>
@endif
@endsection

@push('styles')
<style>
    :root {
        --game-ink: #102033;
        --game-deep: #0f172a;
        --game-orange: #f7941d;
        --game-gold: #ffd18b;
        --game-cyan: #84e4ff;
        --game-sand: #fff7ea;
        --game-line: rgba(15, 23, 42, 0.1);
        --game-shadow: 0 24px 48px rgba(16, 32, 51, 0.12);
    }

    .headphone-game-hero {
        padding: 40px 0 10px;
    }

    .headphone-game-hero-card {
        background:
            radial-gradient(circle at top left, rgba(247, 148, 29, 0.28), transparent 32%),
            radial-gradient(circle at bottom right, rgba(132, 228, 255, 0.18), transparent 24%),
            linear-gradient(135deg, #0f172a 0%, #18354f 52%, #234967 100%);
        border-radius: 32px;
        box-shadow: var(--game-shadow);
        color: #fff;
        overflow: hidden;
        padding: 42px;
        position: relative;
    }

    .headphone-game-hero-card::after {
        background: rgba(255, 255, 255, 0.05);
        border-radius: 999px;
        content: "";
        height: 240px;
        position: absolute;
        right: -70px;
        top: -90px;
        width: 240px;
    }

    .headphone-game-kicker {
        color: var(--game-gold);
        display: inline-block;
        font-size: 0.8rem;
        font-weight: 700;
        letter-spacing: 0.18em;
        margin-bottom: 18px;
        text-transform: uppercase;
    }

    .headphone-game-hero-card h1 {
        color: #fff;
        font-size: clamp(2rem, 3.7vw, 3.5rem);
        line-height: 1.06;
        margin-bottom: 16px;
    }

    .headphone-game-hero-card p {
        color: rgba(255, 255, 255, 0.82);
        max-width: 680px;
    }

    .headphone-game-hero-actions,
    .headphone-game-product-actions,
    .headphone-game-end-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
    }

    .headphone-game-primary-btn,
    .headphone-game-ghost-btn,
    .headphone-game-sound-btn,
    .headphone-game-control-btn {
        align-items: center;
        border-radius: 999px;
        display: inline-flex;
        font-weight: 700;
        justify-content: center;
        padding: 14px 22px;
        transition: all 0.2s ease;
    }

    .headphone-game-primary-btn {
        background: var(--game-orange);
        border: 1px solid var(--game-orange);
        color: #fff;
    }

    .headphone-game-primary-btn:hover,
    .headphone-game-primary-btn:focus {
        background: #ff8b00;
        border-color: #ff8b00;
        color: #fff;
    }

    .headphone-game-ghost-btn {
        background: transparent;
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: inherit;
    }

    .headphone-game-shell .headphone-game-ghost-btn,
    .headphone-game-result .headphone-game-ghost-btn {
        border-color: rgba(15, 23, 42, 0.14);
        color: var(--game-ink);
    }

    .headphone-game-shell .headphone-game-ghost-btn:hover,
    .headphone-game-result .headphone-game-ghost-btn:hover {
        background: rgba(247, 148, 29, 0.08);
        color: var(--game-ink);
    }

    .headphone-game-hero-meter {
        background: rgba(255, 255, 255, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.14);
        border-radius: 28px;
        padding: 28px;
        position: relative;
        z-index: 1;
    }

    .headphone-game-meter-ring {
        align-items: center;
        background:
            radial-gradient(circle, rgba(255, 255, 255, 0.18) 0%, rgba(255, 255, 255, 0.04) 64%, transparent 66%),
            conic-gradient(from 180deg, rgba(247, 148, 29, 0.95), rgba(132, 228, 255, 0.55), rgba(255, 255, 255, 0.2));
        border: 1px solid rgba(255, 255, 255, 0.14);
        border-radius: 999px;
        display: flex;
        flex-direction: column;
        height: 160px;
        justify-content: center;
        margin: 0 auto 22px;
        width: 160px;
    }

    .headphone-game-meter-ring strong {
        font-size: 2.2rem;
        line-height: 1;
    }

    .headphone-game-meter-ring span,
    .headphone-game-meter-list li {
        color: rgba(255, 255, 255, 0.8);
    }

    .headphone-game-meter-list {
        list-style: none;
        margin: 0;
        padding: 0;
    }

    .headphone-game-meter-list li {
        border-top: 1px solid rgba(255, 255, 255, 0.12);
        padding: 12px 0;
    }

    .headphone-game-meter-list li:first-child {
        border-top: 0;
        padding-top: 0;
    }

    .headphone-game-shell {
        padding-top: 20px;
    }

    .headphone-game-card,
    .headphone-game-side-card,
    .headphone-game-result-intro,
    .headphone-game-product-card,
    .headphone-game-reading-card {
        background: #fff;
        border: 1px solid var(--game-line);
        border-radius: 28px;
        box-shadow: var(--game-shadow);
    }

    .headphone-game-card,
    .headphone-game-side-card {
        padding: 28px;
    }

    .headphone-game-hud {
        align-items: center;
        background: var(--game-sand);
        border-radius: 22px;
        display: grid;
        gap: 12px;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        padding: 16px;
    }

    .headphone-game-stat {
        background: rgba(255, 255, 255, 0.75);
        border-radius: 18px;
        padding: 12px 14px;
    }

    .headphone-game-stat span {
        color: #6b7b88;
        display: block;
        font-size: 0.82rem;
    }

    .headphone-game-stat strong {
        color: var(--game-ink);
        display: block;
        font-size: 1.1rem;
        margin-top: 4px;
    }

    .headphone-game-sound-btn {
        background: #102033;
        border: 1px solid #102033;
        color: #fff;
        min-height: 56px;
        width: 100%;
    }

    .headphone-game-sound-btn.is-muted {
        background: transparent;
        color: var(--game-ink);
    }

    .headphone-game-live-tags,
    .headphone-game-chip-group,
    .headphone-game-result-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .headphone-game-live-tags {
        margin: 18px 0 16px;
    }

    .headphone-game-live-tags span,
    .headphone-game-chip-group span,
    .headphone-game-result-tags span {
        background: rgba(247, 148, 29, 0.1);
        border-radius: 999px;
        color: #8a5a11;
        font-size: 0.84rem;
        font-weight: 700;
        padding: 10px 14px;
    }

    .headphone-game-stage {
        background:
            linear-gradient(180deg, rgba(132, 228, 255, 0.18) 0%, rgba(248, 250, 252, 0.9) 34%, #ffffff 100%);
        border: 1px solid rgba(15, 23, 42, 0.08);
        border-radius: 28px;
        cursor: ew-resize;
        height: 560px;
        isolation: isolate;
        overflow: hidden;
        position: relative;
        touch-action: none;
        user-select: none;
    }

    .headphone-game-stage::after {
        background: linear-gradient(180deg, transparent 0%, rgba(16, 32, 51, 0.08) 100%);
        bottom: 0;
        content: "";
        height: 110px;
        left: 0;
        position: absolute;
        right: 0;
        z-index: 0;
    }

    .headphone-game-scene-props {
        inset: 0;
        pointer-events: none;
        position: absolute;
        z-index: 1;
    }

    .headphone-game-scene-prop {
        opacity: 0;
        position: absolute;
        transition: opacity 0.35s ease;
    }

    .headphone-game-scene-prop--warm {
        background: radial-gradient(circle, rgba(255, 213, 138, 0.78) 0%, rgba(255, 213, 138, 0.28) 36%, transparent 72%);
        border-radius: 999px;
        filter: blur(16px);
        height: 220px;
        left: -18px;
        mix-blend-mode: screen;
        top: -22px;
        width: 220px;
    }

    .headphone-game-scene-prop--cup {
        background: linear-gradient(180deg, rgba(255, 255, 255, 0.92) 0%, rgba(234, 179, 8, 0.88) 100%);
        border-radius: 18px 18px 24px 24px;
        bottom: 36px;
        box-shadow: 0 18px 30px rgba(15, 23, 42, 0.16);
        height: 54px;
        left: 24px;
        transform: rotate(-6deg);
        width: clamp(54px, 10vw, 70px);
    }

    .headphone-game-scene-prop--cup::before {
        border: 6px solid rgba(255, 244, 220, 0.92);
        border-left: 0;
        border-radius: 0 16px 16px 0;
        content: "";
        height: 22px;
        position: absolute;
        right: -14px;
        top: 14px;
        width: 16px;
    }

    .headphone-game-scene-prop--cup::after {
        background: rgba(255, 255, 255, 0.2);
        border-radius: 999px;
        content: "";
        height: 8px;
        left: 10px;
        position: absolute;
        right: 10px;
        top: 8px;
    }

    .headphone-game-scene-prop--steam {
        background: linear-gradient(180deg, rgba(255, 255, 255, 0) 0%, rgba(255, 255, 255, 0.46) 46%, rgba(255, 255, 255, 0) 100%);
        border-radius: 999px;
        bottom: 94px;
        filter: blur(4px);
        height: 72px;
        width: 16px;
    }

    .headphone-game-scene-prop--steam-a {
        left: 38px;
        transform: rotate(-8deg);
    }

    .headphone-game-scene-prop--steam-b {
        bottom: 102px;
        left: 62px;
        transform: rotate(8deg);
    }

    .headphone-game-scene-prop--streak {
        background: linear-gradient(90deg, rgba(255, 255, 255, 0) 0%, rgba(255, 255, 255, 0.7) 48%, rgba(132, 228, 255, 0.86) 76%, rgba(255, 255, 255, 0) 100%);
        border-radius: 999px;
        filter: blur(1px);
        height: 6px;
        left: -180px;
        transform: skewX(-28deg);
        width: min(26vw, 160px);
    }

    .headphone-game-scene-prop--streak-a {
        top: 92px;
    }

    .headphone-game-scene-prop--streak-b {
        top: 170px;
    }

    .headphone-game-scene-prop--streak-c {
        top: 246px;
    }

    .headphone-game-scene-prop--rgb {
        border-radius: 999px;
        filter: blur(2px);
        mix-blend-mode: screen;
    }

    .headphone-game-scene-prop--rgb-a {
        background: linear-gradient(90deg, rgba(34, 211, 238, 0.9), rgba(96, 165, 250, 0.55));
        bottom: 56px;
        height: 12px;
        left: 34px;
        width: clamp(96px, 18vw, 150px);
    }

    .headphone-game-scene-prop--rgb-b {
        background: linear-gradient(180deg, rgba(236, 72, 153, 0.88), rgba(124, 58, 237, 0.48));
        bottom: 112px;
        height: clamp(96px, 20vw, 140px);
        right: 38px;
        width: 12px;
    }

    .headphone-game-scene-prop--rgb-c {
        background: linear-gradient(90deg, rgba(251, 191, 36, 0.84), rgba(236, 72, 153, 0.7));
        height: 10px;
        right: 88px;
        top: 76px;
        width: clamp(72px, 14vw, 120px);
    }

    .headphone-game-scene-prop--timer {
        align-items: center;
        background: rgba(255, 255, 255, 0.9);
        border: 1px solid rgba(124, 58, 237, 0.14);
        border-radius: 16px;
        box-shadow: 0 16px 28px rgba(15, 23, 42, 0.12);
        color: #4c1d95;
        display: inline-flex;
        font-size: clamp(0.74rem, 2vw, 0.92rem);
        font-weight: 800;
        height: 44px;
        justify-content: center;
        letter-spacing: 0.08em;
        right: 22px;
        text-transform: uppercase;
        top: 26px;
        width: clamp(76px, 18vw, 102px);
    }

    .headphone-game-scene-prop--notebook {
        background:
            linear-gradient(90deg, rgba(124, 58, 237, 0.18) 0 12px, rgba(255, 255, 255, 0.96) 12px 100%),
            repeating-linear-gradient(180deg, rgba(148, 163, 184, 0.22) 0 2px, transparent 2px 18px),
            #ffffff;
        border-radius: 20px;
        bottom: 34px;
        box-shadow: 0 20px 32px rgba(15, 23, 42, 0.16);
        height: clamp(108px, 24vw, 132px);
        right: 28px;
        transform: rotate(-8deg);
        width: clamp(92px, 21vw, 118px);
    }

    .headphone-game-scene-prop--notebook::before {
        background: rgba(255, 255, 255, 0.72);
        border-radius: 999px;
        box-shadow:
            0 18px 0 rgba(255, 255, 255, 0.72),
            0 36px 0 rgba(255, 255, 255, 0.72),
            0 54px 0 rgba(255, 255, 255, 0.72);
        content: "";
        height: 6px;
        left: 24px;
        position: absolute;
        right: 18px;
        top: 18px;
    }

    .headphone-game-scene-prop--pen {
        background: linear-gradient(180deg, #fb7185 0%, #f43f5e 100%);
        border-radius: 999px;
        bottom: 72px;
        box-shadow: 0 12px 18px rgba(244, 63, 94, 0.22);
        height: 12px;
        right: 84px;
        transform: rotate(-30deg);
        width: clamp(64px, 15vw, 86px);
    }

    .headphone-game-scene-prop--pen::after {
        background: #fee2e2;
        border-radius: 0 999px 999px 0;
        content: "";
        height: 12px;
        position: absolute;
        right: -10px;
        top: 0;
        width: 18px;
    }

    .headphone-game-card[data-scene="office"] .headphone-game-scene-prop--warm,
    .headphone-game-card[data-scene="office"] .headphone-game-scene-prop--cup,
    .headphone-game-card[data-scene="office"] .headphone-game-scene-prop--steam {
        opacity: 1;
    }

    .headphone-game-card[data-scene="office"] .headphone-game-scene-prop--warm {
        animation: headphone-game-warm-glow 5.2s ease-in-out infinite;
    }

    .headphone-game-card[data-scene="office"] .headphone-game-scene-prop--steam-a {
        animation: headphone-game-steam 2.8s ease-in-out infinite;
    }

    .headphone-game-card[data-scene="office"] .headphone-game-scene-prop--steam-b {
        animation: headphone-game-steam 3.2s ease-in-out infinite 0.6s;
    }

    .headphone-game-card[data-scene="running"] .headphone-game-scene-prop--streak {
        opacity: 0.92;
        animation: headphone-game-commute 2.2s linear infinite;
    }

    .headphone-game-card[data-scene="running"] .headphone-game-scene-prop--streak-b {
        animation-delay: 0.45s;
    }

    .headphone-game-card[data-scene="running"] .headphone-game-scene-prop--streak-c {
        animation-delay: 0.95s;
    }

    .headphone-game-card[data-scene="gaming"] .headphone-game-scene-prop--rgb {
        opacity: 0.9;
        animation: headphone-game-rgb-pulse 3.4s ease-in-out infinite;
    }

    .headphone-game-card[data-scene="gaming"] .headphone-game-scene-prop--rgb-b {
        animation-duration: 2.7s;
        animation-delay: 0.35s;
    }

    .headphone-game-card[data-scene="gaming"] .headphone-game-scene-prop--rgb-c {
        animation-duration: 2.9s;
        animation-delay: 0.8s;
    }

    .headphone-game-card[data-scene="study"] .headphone-game-scene-prop--timer,
    .headphone-game-card[data-scene="study"] .headphone-game-scene-prop--notebook,
    .headphone-game-card[data-scene="study"] .headphone-game-scene-prop--pen {
        opacity: 1;
    }

    .headphone-game-card[data-scene="study"] .headphone-game-scene-prop--timer {
        animation: headphone-game-timer-tick 2.8s ease-in-out infinite;
    }

    .headphone-game-card[data-scene="study"] .headphone-game-scene-prop--notebook {
        animation: headphone-game-notebook-bob 4.8s ease-in-out infinite;
    }

    .headphone-game-card[data-scene="study"] .headphone-game-scene-prop--pen {
        animation: headphone-game-pen-bob 4.8s ease-in-out infinite 0.3s;
    }

    @keyframes headphone-game-warm-glow {
        0%,
        100% {
            opacity: 0.7;
            transform: scale(1);
        }

        50% {
            opacity: 0.92;
            transform: scale(1.07);
        }
    }

    @keyframes headphone-game-steam {
        0% {
            opacity: 0;
            transform: translateY(8px) scale(0.94);
        }

        25% {
            opacity: 0.42;
        }

        100% {
            opacity: 0;
            transform: translateY(-36px) scale(1.2);
        }
    }

    @keyframes headphone-game-commute {
        0% {
            transform: translateX(-8%) skewX(-28deg);
        }

        100% {
            transform: translateX(calc(100vw + 180px)) skewX(-28deg);
        }
    }

    @keyframes headphone-game-rgb-pulse {
        0%,
        100% {
            filter: blur(2px);
            opacity: 0.56;
            transform: scale(1);
        }

        50% {
            filter: blur(3px);
            opacity: 1;
            transform: scale(1.08);
        }
    }

    @keyframes headphone-game-timer-tick {
        0%,
        100% {
            transform: translateY(0) scale(1);
        }

        50% {
            transform: translateY(-3px) scale(1.04);
        }
    }

    @keyframes headphone-game-notebook-bob {
        0%,
        100% {
            transform: translateY(0) rotate(-8deg);
        }

        50% {
            transform: translateY(-5px) rotate(-6deg);
        }
    }

    @keyframes headphone-game-pen-bob {
        0%,
        100% {
            transform: translateY(0) rotate(-30deg);
        }

        50% {
            transform: translateY(-4px) rotate(-26deg);
        }
    }

    .headphone-game-card[data-scene="office"] .headphone-game-hud {
        background: linear-gradient(135deg, rgba(250, 245, 235, 0.98), rgba(239, 249, 255, 0.96));
    }

    .headphone-game-card[data-scene="office"] .headphone-game-stage {
        background:
            linear-gradient(180deg, rgba(255, 245, 224, 0.58) 0%, rgba(255, 250, 240, 0.74) 36%, rgba(255, 253, 247, 0.88) 100%),
            radial-gradient(circle at 18% 18%, rgba(247, 198, 120, 0.26), transparent 24%),
            radial-gradient(circle at 82% 16%, rgba(132, 228, 255, 0.18), transparent 28%),
            url("{{ asset('frontend/img/quiz-scenes/cafe.jpg') }}") center/cover no-repeat;
    }

    .headphone-game-card[data-scene="running"] .headphone-game-hud {
        background: linear-gradient(135deg, rgba(232, 245, 255, 0.98), rgba(237, 255, 248, 0.96));
    }

    .headphone-game-card[data-scene="running"] .headphone-game-stage {
        background:
            linear-gradient(180deg, rgba(219, 234, 254, 0.52) 0%, rgba(240, 249, 255, 0.68) 36%, rgba(248, 253, 255, 0.84) 100%),
            radial-gradient(circle at 16% 18%, rgba(16, 185, 129, 0.2), transparent 26%),
            linear-gradient(110deg, rgba(255, 255, 255, 0.16) 0%, rgba(255, 255, 255, 0) 18%, rgba(255, 255, 255, 0.18) 36%, rgba(255, 255, 255, 0) 54%, rgba(255, 255, 255, 0.14) 72%, rgba(255, 255, 255, 0) 100%),
            url("{{ asset('frontend/img/quiz-scenes/commute.jpg') }}") center/cover no-repeat;
    }

    .headphone-game-card[data-scene="gaming"] .headphone-game-hud {
        background: linear-gradient(135deg, rgba(23, 23, 35, 0.98), rgba(36, 25, 68, 0.94));
    }

    .headphone-game-card[data-scene="gaming"] .headphone-game-hud .headphone-game-stat {
        background: rgba(255, 255, 255, 0.08);
    }

    .headphone-game-card[data-scene="gaming"] .headphone-game-hud .headphone-game-stat span {
        color: rgba(226, 232, 240, 0.8);
    }

    .headphone-game-card[data-scene="gaming"] .headphone-game-hud .headphone-game-stat strong {
        color: #f8fafc;
    }

    .headphone-game-card[data-scene="gaming"] .headphone-game-stage {
        background:
            linear-gradient(180deg, rgba(15, 23, 42, 0.48) 0%, rgba(30, 27, 75, 0.7) 42%, rgba(17, 24, 39, 0.88) 100%),
            radial-gradient(circle at 18% 16%, rgba(99, 102, 241, 0.22), transparent 24%),
            radial-gradient(circle at 82% 18%, rgba(236, 72, 153, 0.18), transparent 28%),
            url("{{ asset('frontend/img/quiz-scenes/gaming.jpg') }}") center/cover no-repeat;
    }

    .headphone-game-card[data-scene="study"] .headphone-game-hud {
        background: linear-gradient(135deg, rgba(249, 245, 255, 0.98), rgba(255, 251, 235, 0.96));
    }

    .headphone-game-card[data-scene="study"] .headphone-game-stage {
        background:
            linear-gradient(180deg, rgba(245, 243, 255, 0.56) 0%, rgba(255, 251, 235, 0.72) 36%, rgba(255, 253, 248, 0.88) 100%),
            radial-gradient(circle at 18% 16%, rgba(192, 132, 252, 0.2), transparent 24%),
            radial-gradient(circle at 84% 16%, rgba(251, 191, 36, 0.14), transparent 26%),
            url("{{ asset('frontend/img/quiz-scenes/study.jpg') }}") center/cover no-repeat;
    }

    .headphone-game-player {
        bottom: 28px;
        left: 50%;
        pointer-events: none;
        position: absolute;
        transform: translateX(-50%);
        z-index: 3;
    }

    .headphone-game-player__halo {
        background: radial-gradient(circle, rgba(247, 148, 29, 0.28) 0%, transparent 70%);
        border-radius: 999px;
        display: block;
        height: 84px;
        left: 50%;
        position: absolute;
        top: 50%;
        transform: translate(-50%, -50%);
        width: 84px;
    }

    .headphone-game-player__icon {
        align-items: center;
        background: linear-gradient(180deg, #102033 0%, #1b3b56 100%);
        border: 2px solid rgba(255, 255, 255, 0.22);
        border-radius: 22px;
        box-shadow: 0 18px 24px rgba(16, 32, 51, 0.22);
        color: #fff;
        display: flex;
        font-size: 1.2rem;
        font-weight: 800;
        letter-spacing: 0.08em;
        height: 68px;
        justify-content: center;
        position: relative;
        width: 92px;
        z-index: 1;
    }

    .headphone-game-item {
        align-items: center;
        border-radius: 18px;
        color: #fff;
        display: inline-flex;
        flex-direction: column;
        font-size: 0.78rem;
        font-weight: 700;
        gap: 6px;
        justify-content: center;
        min-height: 70px;
        min-width: 76px;
        padding: 10px 12px;
        pointer-events: none;
        position: absolute;
        text-align: center;
        z-index: 2;
    }

    .headphone-game-item__glyph {
        background: rgba(255, 255, 255, 0.18);
        border-radius: 999px;
        display: inline-flex;
        font-size: 0.72rem;
        font-weight: 800;
        letter-spacing: 0.08em;
        min-width: 36px;
        padding: 6px 8px;
        text-transform: uppercase;
    }

    .headphone-game-item.is-office { background: linear-gradient(180deg, #2563eb 0%, #1d4ed8 100%); }
    .headphone-game-item.is-gaming { background: linear-gradient(180deg, #ef4444 0%, #dc2626 100%); }
    .headphone-game-item.is-running { background: linear-gradient(180deg, #10b981 0%, #059669 100%); }
    .headphone-game-item.is-study { background: linear-gradient(180deg, #7c3aed 0%, #6d28d9 100%); }
    .headphone-game-item.is-focus { background: linear-gradient(180deg, #0ea5e9 0%, #0284c7 100%); }
    .headphone-game-item.is-mic { background: linear-gradient(180deg, #f97316 0%, #ea580c 100%); }
    .headphone-game-item.is-comfort { background: linear-gradient(180deg, #14b8a6 0%, #0f766e 100%); }
    .headphone-game-item.is-value { background: linear-gradient(180deg, #f59e0b 0%, #d97706 100%); }
    .headphone-game-item.is-over-ear { background: linear-gradient(180deg, #334155 0%, #1e293b 100%); }
    .headphone-game-item.is-wireless { background: linear-gradient(180deg, #8b5cf6 0%, #7c3aed 100%); }
    .headphone-game-item.is-budget-low { background: linear-gradient(180deg, #84cc16 0%, #65a30d 100%); }
    .headphone-game-item.is-budget-mid { background: linear-gradient(180deg, #06b6d4 0%, #0891b2 100%); }
    .headphone-game-item.is-budget-high { background: linear-gradient(180deg, #ec4899 0%, #db2777 100%); }
    .headphone-game-item.is-noise {
        background: linear-gradient(180deg, #475569 0%, #1f2937 100%);
        box-shadow: inset 0 0 0 2px rgba(255, 255, 255, 0.12);
    }

    .headphone-game-overlay {
        align-items: center;
        background: rgba(15, 23, 42, 0.78);
        color: #fff;
        display: none;
        flex-direction: column;
        inset: 0;
        justify-content: center;
        overflow-y: auto;
        overscroll-behavior: contain;
        padding: 28px;
        position: absolute;
        text-align: center;
        z-index: 5;
    }

    .headphone-game-overlay.is-active {
        display: flex;
    }

    .headphone-game-overlay[data-game-start-screen] {
        justify-content: flex-start;
        padding-bottom: 34px;
        padding-top: 34px;
    }

    .headphone-game-overlay h2 {
        color: #fff;
        font-size: clamp(1.8rem, 3vw, 2.6rem);
        margin: 12px 0;
    }

    .headphone-game-overlay p {
        color: rgba(255, 255, 255, 0.82);
        margin-bottom: 20px;
        max-width: 560px;
    }

    .headphone-game-scene-grid {
        display: grid;
        gap: 12px;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        margin: 6px 0 18px;
        width: min(100%, 700px);
    }

    .headphone-game-scene-btn {
        background-color: #1f2937;
        border: 1px solid rgba(255, 255, 255, 0.16);
        border-radius: 22px;
        background-position: center;
        background-repeat: no-repeat;
        background-size: cover;
        box-shadow: inset 0 -72px 72px rgba(15, 23, 42, 0.45);
        color: #fff;
        cursor: pointer;
        min-height: 110px;
        overflow: hidden;
        padding: 18px 18px 16px;
        position: relative;
        text-align: left;
        transition: transform 0.18s ease, border-color 0.18s ease, background 0.18s ease, box-shadow 0.18s ease;
    }

    .headphone-game-scene-btn[data-scene-choice="office"] {
        background-image:
            linear-gradient(180deg, rgba(60, 41, 24, 0.12) 0%, rgba(32, 25, 16, 0.7) 100%),
            url("{{ asset('frontend/img/quiz-scenes/cafe.jpg') }}");
    }

    .headphone-game-scene-btn[data-scene-choice="running"] {
        background-image:
            linear-gradient(180deg, rgba(12, 74, 110, 0.08) 0%, rgba(15, 23, 42, 0.68) 100%),
            url("{{ asset('frontend/img/quiz-scenes/commute.jpg') }}");
    }

    .headphone-game-scene-btn[data-scene-choice="gaming"] {
        background-image:
            linear-gradient(180deg, rgba(91, 33, 182, 0.12) 0%, rgba(15, 23, 42, 0.72) 100%),
            url("{{ asset('frontend/img/quiz-scenes/gaming.jpg') }}");
    }

    .headphone-game-scene-btn[data-scene-choice="study"] {
        background-image:
            linear-gradient(180deg, rgba(88, 28, 135, 0.08) 0%, rgba(15, 23, 42, 0.66) 100%),
            url("{{ asset('frontend/img/quiz-scenes/study.jpg') }}");
    }

    .headphone-game-scene-btn strong,
    .headphone-game-scene-btn span {
        display: block;
        position: relative;
        z-index: 1;
    }

    .headphone-game-scene-btn strong {
        color: #fff;
        font-size: 1rem;
        margin-bottom: 6px;
    }

    .headphone-game-scene-btn span {
        color: rgba(255, 255, 255, 0.72);
        font-size: 0.86rem;
        line-height: 1.5;
    }

    .headphone-game-scene-btn:hover,
    .headphone-game-scene-btn:focus {
        border-color: rgba(255, 255, 255, 0.3);
        box-shadow: inset 0 -84px 84px rgba(15, 23, 42, 0.52), 0 12px 30px rgba(15, 23, 42, 0.18);
        transform: translateY(-2px);
    }

    .headphone-game-scene-btn.is-active {
        border-color: rgba(255, 209, 139, 0.9);
        box-shadow: inset 0 -92px 92px rgba(15, 23, 42, 0.56), 0 18px 36px rgba(15, 23, 42, 0.2);
    }

    .headphone-game-start-meta {
        background: rgba(255, 255, 255, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.12);
        border-radius: 18px;
        color: rgba(255, 255, 255, 0.74);
        line-height: 1.6;
        margin-bottom: 20px;
        max-width: 700px;
        padding: 14px 18px;
        font-size: 0.88rem;
        font-weight: 600;
        width: min(100%, 700px);
    }

    .headphone-game-primary-btn[disabled] {
        cursor: not-allowed;
        opacity: 0.58;
    }

    .headphone-game-mobile-controls {
        display: flex;
        gap: 12px;
        margin-top: 16px;
    }

    .headphone-game-control-btn {
        background: #102033;
        border: 1px solid #102033;
        color: #fff;
        flex: 1 1 0;
    }

    .headphone-game-hidden-form {
        display: none;
    }

    .headphone-game-side-card {
        background:
            radial-gradient(circle at top right, rgba(247, 148, 29, 0.18), transparent 32%),
            linear-gradient(180deg, #fffdf9 0%, #fff 100%);
        height: 100%;
    }

    .headphone-game-side-card h3 {
        color: var(--game-ink);
        font-size: 1.7rem;
        line-height: 1.18;
        margin-bottom: 22px;
    }

    .headphone-game-side-points {
        display: grid;
        gap: 14px;
    }

    .headphone-game-side-points article {
        background: rgba(255, 255, 255, 0.84);
        border: 1px solid rgba(15, 23, 42, 0.08);
        border-radius: 18px;
        padding: 16px 18px;
    }

    .headphone-game-side-points strong {
        color: var(--game-ink);
        display: block;
        margin-bottom: 6px;
    }

    .headphone-game-side-points p,
    .headphone-game-side-note li {
        color: #61717e;
        margin-bottom: 0;
    }

    .headphone-game-side-note {
        background: #102033;
        border-radius: 22px;
        color: #fff;
        margin-top: 18px;
        padding: 18px 20px;
    }

    .headphone-game-side-note span {
        color: var(--game-gold);
        display: block;
        font-weight: 700;
        margin-bottom: 10px;
    }

    .headphone-game-side-note ul {
        margin: 0;
        padding-left: 18px;
    }

    .headphone-game-result {
        padding-top: 0;
    }

    .headphone-game-result-intro {
        margin-bottom: 24px;
        padding: 30px;
    }

    .headphone-game-result-intro h2,
    .headphone-game-reading-body h3 {
        color: var(--game-ink);
    }

    .headphone-game-result-intro p,
    .headphone-game-reading-body p {
        color: #5b6975;
    }

    .headphone-game-product-card {
        height: calc(100% - 24px);
        margin-bottom: 24px;
        overflow: hidden;
    }

    .headphone-game-product-media {
        background:
            radial-gradient(circle at top, rgba(255, 255, 255, 0.98), rgba(226, 232, 240, 0.92)),
            #eef2ff;
        border-bottom: 1px solid rgba(226, 232, 240, 0.82);
        min-height: 292px;
        overflow: hidden;
        padding: 18px 18px 12px;
        position: relative;
    }

    .headphone-game-product-media__shell {
        --headphone-game-product-image-width: 220px;
        --headphone-game-product-image: none;
        align-items: center;
        border-radius: 22px;
        display: flex;
        justify-content: center;
        isolation: isolate;
        min-height: 250px;
        overflow: hidden;
        position: relative;
    }

    .headphone-game-product-media__shell::before {
        background-image: var(--headphone-game-product-image);
        background-position: center;
        background-repeat: no-repeat;
        background-size: contain;
        content: "";
        inset: 12%;
        filter: blur(28px) saturate(1.08);
        opacity: 0.14;
        position: absolute;
        transform: scale(1.08);
    }

    .headphone-game-product-media__image {
        display: block;
        filter: drop-shadow(0 18px 30px rgba(15, 23, 42, 0.18));
        max-height: 250px;
        max-width: min(100%, var(--headphone-game-product-image-width));
        object-fit: contain;
        position: relative;
        transition: transform 0.24s ease, filter 0.24s ease;
        width: auto;
        z-index: 1;
    }

    .headphone-game-product-card:hover .headphone-game-product-media__image {
        filter: drop-shadow(0 24px 34px rgba(15, 23, 42, 0.22));
        transform: translateY(-4px);
    }

    .headphone-game-product-media__image--enhanced {
        border-radius: 18px;
        box-shadow:
            0 14px 24px rgba(15, 23, 42, 0.12),
            0 0 0 1px rgba(226, 232, 240, 0.95);
        max-width: min(100%, var(--headphone-game-product-image-width, 170px));
    }

    .headphone-game-product-media--low-res:hover .headphone-game-product-media__image {
        transform: translateY(-1px);
    }

    .headphone-game-product-media span {
        background: rgba(15, 23, 42, 0.82);
        border-radius: 999px;
        color: #fff;
        font-size: 0.8rem;
        font-weight: 700;
        left: 18px;
        padding: 10px 12px;
        position: absolute;
        top: 18px;
    }

    .headphone-game-product-body {
        padding: 22px;
    }

    .headphone-game-product-meta {
        color: #8a5a11;
        font-size: 0.82rem;
        font-weight: 700;
        letter-spacing: 0.12em;
        margin-bottom: 10px;
        text-transform: uppercase;
    }

    .headphone-game-product-body h3 {
        font-size: 1.3rem;
        line-height: 1.28;
        margin-bottom: 14px;
    }

    .headphone-game-product-body h3 a {
        color: var(--game-ink);
    }

    .headphone-game-product-price {
        align-items: baseline;
        display: flex;
        gap: 12px;
        margin-bottom: 16px;
    }

    .headphone-game-product-price strong {
        color: var(--game-orange);
        font-size: 1.45rem;
        line-height: 1;
    }

    .headphone-game-product-price del {
        color: #94a3b8;
    }

    .headphone-game-reason-list {
        color: #536270;
        margin: 0 0 18px;
        padding-left: 18px;
    }

    .headphone-game-reason-list li + li {
        margin-top: 8px;
    }

    .headphone-game-reading-card {
        margin-top: 10px;
        overflow: hidden;
        padding: 18px;
    }

    .headphone-game-reading-media img {
        border-radius: 24px;
        display: block;
        height: 100%;
        min-height: 280px;
        object-fit: cover;
        width: 100%;
    }

    .headphone-game-reading-body {
        padding: 10px 8px 10px 14px;
    }

    .headphone-game-reading-reason {
        color: #8a5a11;
        font-weight: 600;
    }

    @media (max-width: 991.98px) {
        .headphone-game-hero-card,
        .headphone-game-card,
        .headphone-game-side-card,
        .headphone-game-result-intro {
            border-radius: 24px;
            padding: 24px;
        }

        .headphone-game-hero-meter {
            margin-top: 22px;
        }

        .headphone-game-side-card {
            margin-top: 24px;
        }

        .headphone-game-hud {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 767.98px) {
        .headphone-game-hero {
            padding-top: 24px;
        }

        .headphone-game-hero-card {
            padding: 22px;
        }

        .headphone-game-hero-actions,
        .headphone-game-product-actions,
        .headphone-game-end-actions {
            flex-direction: column;
        }

        .headphone-game-primary-btn,
        .headphone-game-ghost-btn {
            width: 100%;
        }

        .headphone-game-scene-grid {
            grid-template-columns: 1fr;
        }

        .headphone-game-stage {
            height: 520px;
        }

        .headphone-game-reading-media img {
            height: 220px;
            min-height: 220px;
        }

        .headphone-game-product-media {
            min-height: 258px;
        }

        .headphone-game-product-media__shell {
            min-height: 210px;
        }

        .headphone-game-product-media__image {
            max-height: 210px;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    (function () {
        var gameCard = document.querySelector('[data-game-card]');
        var stage = document.querySelector('[data-game-stage]');
        var player = document.querySelector('[data-game-player]');
        var timerNode = document.querySelector('[data-game-timer]');
        var scoreNode = document.querySelector('[data-game-score]');
        var waveNode = document.querySelector('[data-game-wave]');
        var startScreen = document.querySelector('[data-game-start-screen]');
        var endScreen = document.querySelector('[data-game-end-screen]');
        var endTitle = document.querySelector('[data-end-title]');
        var endCopy = document.querySelector('[data-end-copy]');
        var endTags = document.querySelector('[data-end-tags]');
        var startButton = document.querySelector('[data-game-start]');
        var restartButton = document.querySelector('[data-game-restart]');
        var submitButton = document.querySelector('[data-submit-recommendation]');
        var soundButton = document.querySelector('[data-sound-toggle]');
        var form = document.getElementById('headphone-game-form');
        var resultSection = document.getElementById('quiz-result');
        var startTitleNode = document.querySelector('[data-game-start-title]');
        var startCopyNode = document.querySelector('[data-game-start-copy]');
        var sceneMetaNode = document.querySelector('[data-game-scene-meta]');
        var sceneButtons = Array.prototype.slice.call(document.querySelectorAll('[data-scene-choice]'));
        var tagNodes = {};
        var controlButtons = Array.prototype.slice.call(document.querySelectorAll('[data-control]'));

        if (!stage || !player || !form) {
            return;
        }

        Array.prototype.forEach.call(document.querySelectorAll('[data-game-tag]'), function (node) {
            tagNodes[node.getAttribute('data-game-tag')] = node;
        });

        var sceneDefinitions = {
            office: {
                label: 'Cafe tập trung',
                waveLabel: 'Cafe tập trung',
                title: 'Scene cafe đã sẵn sàng.',
                copy: 'Không gian nền chuyển sang quán cafe tập trung. Chặng mở màn sẽ nghiêng về chống ồn nhẹ, mic rõ và giữ flow làm việc.',
                meta: 'Scene này ưu tiên item kiểu tập trung, mic và over-ear để mở đúng mood cafe.',
                pool: ['office', 'focus', 'mic', 'over_ear', 'noise']
            },
            running: {
                label: 'Commute',
                waveLabel: 'Commute',
                title: 'Scene commute đã lên sân.',
                copy: 'Nền game chuyển sang nhịp di chuyển liên tục. Wave đầu sẽ thiên về gọn nhẹ, true wireless và phản xạ bắt nhanh.',
                meta: 'Scene này nhấn mạnh item di chuyển, true wireless và comfort để ra hồ sơ linh hoạt.',
                pool: ['running', 'true_wireless', 'comfort', 'value', 'noise']
            },
            gaming: {
                label: 'Gaming đêm',
                waveLabel: 'Gaming đêm',
                title: 'Scene gaming đêm đã bật đèn.',
                copy: 'Sân chơi đổi sang desk tối kiểu gaming. Chặng mở màn sẽ dồn nhiều item mic, headset chụp tai và phản xạ vào trận nhanh.',
                meta: 'Scene này ưu tiên item gaming, mic và over-ear để đẩy hồ sơ callout rõ, vào trận gắt.',
                pool: ['gaming', 'mic', 'over_ear', 'focus', 'noise']
            },
            study: {
                label: 'Học 4 tiếng',
                waveLabel: 'Học 4 tiếng',
                title: 'Scene học dài hơi đã mở.',
                copy: 'Nền game chuyển sang góc học yên. Wave đầu sẽ nghiêng về comfort, tập trung và cảm giác đeo lâu không mệt.',
                meta: 'Scene này thiên về item study, comfort và focus để khóa hồ sơ đeo lâu, học sâu.',
                pool: ['study', 'comfort', 'focus', 'value', 'noise']
            }
        };

        var itemDefinitions = {
            office: { label: 'Việc', glyph: 'LV', className: 'is-office', points: 12 },
            gaming: { label: 'Game', glyph: 'GM', className: 'is-gaming', points: 12 },
            running: { label: 'Chạy', glyph: 'CH', className: 'is-running', points: 12 },
            study: { label: 'Êm', glyph: 'ÊM', className: 'is-study', points: 12 },
            focus: { label: 'Tĩnh', glyph: 'TĨ', className: 'is-focus', points: 10 },
            mic: { label: 'Mic', glyph: 'MIC', className: 'is-mic', points: 10 },
            comfort: { label: 'Êm', glyph: 'ÊM', className: 'is-comfort', points: 10 },
            value: { label: 'Hời', glyph: 'HỜI', className: 'is-value', points: 10 },
            over_ear: { label: 'Chụp', glyph: 'ÔM', className: 'is-over-ear', points: 9 },
            true_wireless: { label: 'TWS', glyph: 'TWS', className: 'is-wireless', points: 9 },
            budget_low: { label: '<1tr', glyph: '<1', className: 'is-budget-low', points: 8 },
            budget_mid: { label: '1-2tr', glyph: '1-2', className: 'is-budget-mid', points: 8 },
            budget_high: { label: '>3tr', glyph: '3+', className: 'is-budget-high', points: 8 },
            noise: { label: 'Ồn', glyph: 'ỒN', className: 'is-noise', points: -12, bad: true }
        };

        var waves = [
            { limit: 10, label: 'Bối cảnh', pool: ['office', 'gaming', 'running', 'study', 'noise'] },
            { limit: 20, label: 'Ưu tiên', pool: ['focus', 'mic', 'comfort', 'value', 'noise'] },
            { limit: 30, label: 'Form & giá', pool: ['over_ear', 'true_wireless', 'budget_low', 'budget_mid', 'budget_high', 'noise'] }
        ];

        var answerLabels = {
            primary_use: {
                office: 'Cafe tập trung',
                running: 'Commute',
                gaming: 'Gaming đêm',
                study: 'Học 4 tiếng'
            },
            form_factor: {
                over_ear: 'Chụp tai / over-ear',
                true_wireless: 'True wireless',
                flexible: 'Linh hoạt'
            },
            priority: {
                noise: 'Tập trung / chống ồn',
                mic: 'Mic rõ / thoại tốt',
                comfort: 'Đeo lâu êm',
                value: 'Giá / hiệu năng'
            },
                budget: {
                    under_1000: 'Dưới 1 triệu',
                    between_1000_2000: '1 - 2 triệu',
                    between_2000_3000: '2 - 3 triệu',
                    over_3000: 'Trên 3 triệu'
            }
        };

        var state = {
            duration: 30,
            playerX: 50,
            moveLeft: false,
            moveRight: false,
            running: false,
            finished: false,
            score: 0,
            remaining: 30,
            lastFrameAt: 0,
            lastSpawnAt: 0,
            waveIndex: 0,
            items: [],
            soundEnabled: true,
            stats: {},
            selectedScene: null
        };

        var initialScene = @json($selectedAnswers['primary_use'] ?? null);
        if (initialScene && sceneDefinitions[initialScene]) {
            state.selectedScene = initialScene;
        }

        var audioContext;

        function resetStats() {
            state.stats = {
                office: 0,
                gaming: 0,
                running: 0,
                study: 0,
                focus: 0,
                mic: 0,
                comfort: 0,
                value: 0,
                over_ear: 0,
                true_wireless: 0,
                budget_low: 0,
                budget_mid: 0,
                budget_high: 0,
                noise_hits: 0
            };
        }

        function getAudioContext() {
            var Context = window.AudioContext || window.webkitAudioContext;
            if (!Context) {
                return null;
            }

            if (!audioContext) {
                audioContext = new Context();
            }

            if (audioContext.state === 'suspended') {
                audioContext.resume();
            }

            return audioContext;
        }

        function playTone(type) {
            if (!state.soundEnabled) {
                return;
            }

            var ctx = getAudioContext();
            if (!ctx) {
                return;
            }

            var map = {
                start: { freq: 640, duration: 0.08, gain: 0.03, wave: 'triangle' },
                good: { freq: 840, duration: 0.06, gain: 0.03, wave: 'triangle' },
                bad: { freq: 220, duration: 0.1, gain: 0.04, wave: 'sawtooth' },
                wave: { freq: 520, duration: 0.08, gain: 0.03, wave: 'sine' },
                end: { freq: 920, duration: 0.12, gain: 0.04, wave: 'triangle' }
            };

            var tone = map[type];
            if (!tone) {
                return;
            }

            var now = ctx.currentTime;
            var oscillator = ctx.createOscillator();
            var gain = ctx.createGain();

            oscillator.type = tone.wave;
            oscillator.frequency.setValueAtTime(tone.freq, now);
            gain.gain.setValueAtTime(0.0001, now);
            gain.gain.exponentialRampToValueAtTime(tone.gain, now + 0.01);
            gain.gain.exponentialRampToValueAtTime(0.0001, now + tone.duration);

            oscillator.connect(gain);
            gain.connect(ctx.destination);
            oscillator.start(now);
            oscillator.stop(now + tone.duration + 0.02);
        }

        function updateSoundButton() {
            if (!soundButton) {
                return;
            }

            soundButton.classList.toggle('is-muted', !state.soundEnabled);
            soundButton.setAttribute('aria-pressed', String(state.soundEnabled));
            soundButton.textContent = state.soundEnabled ? 'Âm thanh: Bật' : 'Âm thanh: Tắt';
        }

        function getSceneConfig() {
            return state.selectedScene ? sceneDefinitions[state.selectedScene] : null;
        }

        function refreshSceneSelectionUi() {
            var sceneConfig = getSceneConfig();

            if (gameCard) {
                if (sceneConfig) {
                    gameCard.setAttribute('data-scene', state.selectedScene);
                } else {
                    gameCard.removeAttribute('data-scene');
                }
            }

            sceneButtons.forEach(function (button) {
                var isActive = button.getAttribute('data-scene-choice') === state.selectedScene;
                button.classList.toggle('is-active', isActive);
                button.setAttribute('aria-pressed', String(isActive));
            });

            if (startTitleNode) {
                startTitleNode.textContent = sceneConfig ? sceneConfig.title : 'Chọn scene để dựng sân chơi.';
            }

            if (startCopyNode) {
                startCopyNode.textContent = sceneConfig
                    ? sceneConfig.copy
                    : 'Chọn một bối cảnh trước khi bắt đầu. Nền game, wave mở màn và hồ sơ gợi ý sẽ đổi theo scene bạn chọn.';
            }

            if (sceneMetaNode) {
                sceneMetaNode.textContent = sceneConfig
                    ? sceneConfig.meta
                    : 'Scene sẽ khóa cho Chặng 1 và đổi hẳn cảnh nền của game.';
            }

            if (startButton) {
                startButton.disabled = !sceneConfig;
                startButton.textContent = sceneConfig
                    ? 'Bắt đầu: ' + sceneConfig.label
                    : 'Chọn bối cảnh để bắt đầu';
            }
        }

        function updateHud() {
            if (timerNode) {
                timerNode.textContent = Math.max(0, Math.ceil(state.remaining)) + 's';
            }

            if (scoreNode) {
                scoreNode.textContent = state.score;
            }

            if (waveNode) {
                var sceneConfig = getSceneConfig();
                waveNode.textContent = state.waveIndex === 0 && sceneConfig ? sceneConfig.waveLabel : waves[state.waveIndex].label;
            }

            Object.keys(tagNodes).forEach(function (key) {
                if (!tagNodes[key]) {
                    return;
                }

                var titleMap = {
                    office: 'Làm việc',
                    gaming: 'Gaming',
                    running: 'Di chuyển',
                    study: 'Êm lâu',
                    focus: 'Tập trung',
                    value: 'Giá ngon'
                };
                tagNodes[key].textContent = titleMap[key] + ': ' + (state.stats[key] || 0);
            });
        }

        function clearItems() {
            state.items.forEach(function (item) {
                if (item.node && item.node.parentNode) {
                    item.node.parentNode.removeChild(item.node);
                }
            });
            state.items = [];
        }

        function setPlayerPosition() {
            player.style.left = state.playerX + '%';
        }

        function setPlayerFromClientX(clientX) {
            var rect = stage.getBoundingClientRect();
            if (!rect.width) {
                return;
            }

            var percent = ((clientX - rect.left) / rect.width) * 100;
            state.playerX = Math.max(7, Math.min(93, percent));
            setPlayerPosition();
        }

        function createItem(type) {
            var definition = itemDefinitions[type];
            if (!definition) {
                return;
            }

            var node = document.createElement('div');
            node.className = 'headphone-game-item ' + definition.className;
            node.innerHTML = '<span class="headphone-game-item__glyph">' + definition.glyph + '</span><span>' + definition.label + '</span>';

            var x = 8 + (Math.random() * 84);
            var speed = 140 + (Math.random() * 90) + (state.waveIndex * 18);

            node.style.left = x + '%';
            node.style.top = '-80px';
            stage.appendChild(node);

            state.items.push({
                type: type,
                node: node,
                x: x,
                y: -80,
                speed: speed
            });
        }

        function currentWaveIndex() {
            for (var index = 0; index < waves.length; index += 1) {
                if ((state.duration - state.remaining) < waves[index].limit) {
                    return index;
                }
            }

            return waves.length - 1;
        }

        function randomItemType() {
            var sceneConfig = getSceneConfig();
            var pool = state.waveIndex === 0 && sceneConfig ? sceneConfig.pool : waves[state.waveIndex].pool;
            return pool[Math.floor(Math.random() * pool.length)];
        }

        function intersects(itemRect, playerRect) {
            return !(
                itemRect.right < playerRect.left ||
                itemRect.left > playerRect.right ||
                itemRect.bottom < playerRect.top ||
                itemRect.top > playerRect.bottom
            );
        }

        function removeItem(item, index) {
            if (item.node && item.node.parentNode) {
                item.node.parentNode.removeChild(item.node);
            }
            state.items.splice(index, 1);
        }

        function collectItem(item, index) {
            var definition = itemDefinitions[item.type];
            if (!definition) {
                removeItem(item, index);
                return;
            }

            if (definition.bad) {
                state.score = Math.max(0, state.score + definition.points);
                state.stats.noise_hits += 1;
                playTone('bad');
            } else {
                state.score += definition.points;
                state.stats[item.type] = (state.stats[item.type] || 0) + 1;
                playTone('good');
            }

            updateHud();
            removeItem(item, index);
        }

        function deriveAnswers() {
            var useScores = {
                office: (state.stats.office * 3) + (state.stats.focus * 1.4) + (state.stats.over_ear * 0.6),
                gaming: (state.stats.gaming * 3) + (state.stats.mic * 1.6) + (state.stats.over_ear * 0.4),
                running: (state.stats.running * 3) + (state.stats.true_wireless * 1.6),
                study: (state.stats.study * 3) + (state.stats.comfort * 1.6) + (state.stats.focus * 0.5)
            };

            var priorityScores = {
                noise: (state.stats.focus * 3) + (state.stats.office * 0.6),
                mic: (state.stats.mic * 3) + (state.stats.gaming * 0.7),
                comfort: (state.stats.comfort * 3) + (state.stats.study * 0.8) + (state.stats.running * 0.3),
                value: (state.stats.value * 3) + (state.stats.budget_low * 0.8) + (state.stats.budget_mid * 0.5)
            };

            var formScores = {
                over_ear: (state.stats.over_ear * 3) + (state.stats.office * 0.6) + (state.stats.gaming * 0.6),
                true_wireless: (state.stats.true_wireless * 3) + (state.stats.running * 0.8) + (state.stats.value * 0.2),
                flexible: (state.stats.value * 1.2) + (state.stats.study * 0.5)
            };

            var budgetScores = {
                under_1000: (state.stats.budget_low * 3) + (state.score <= 65 ? 1 : 0),
                between_1000_2000: (state.stats.budget_mid * 3) + (state.stats.value * 0.5) + (state.score > 65 && state.score <= 120 ? 1 : 0),
                between_2000_3000: (state.stats.focus * 0.8) + (state.stats.comfort * 0.8) + (state.score > 120 ? 1 : 0),
                over_3000: (state.stats.budget_high * 3) + (state.stats.gaming * 0.5) + (state.score > 145 ? 1 : 0)
            };

            function pickTop(scores, fallback) {
                var bestKey = fallback;
                var bestValue = Number.NEGATIVE_INFINITY;

                Object.keys(scores).forEach(function (key) {
                    if (scores[key] > bestValue) {
                        bestValue = scores[key];
                        bestKey = key;
                    }
                });

                return bestKey;
            }

            return {
                primary_use: state.selectedScene || pickTop(useScores, 'office'),
                form_factor: pickTop(formScores, 'flexible'),
                priority: pickTop(priorityScores, 'comfort'),
                budget: pickTop(budgetScores, 'between_1000_2000')
            };
        }

        function fillHiddenAnswers(answers) {
            Object.keys(answers).forEach(function (key) {
                var input = form.querySelector('[data-hidden-answer="' + key + '"]');
                if (input) {
                    input.value = answers[key];
                }
            });
        }

        function labelForAnswer(group, key) {
            if (group === 'primary_use' && sceneDefinitions[key]) {
                return sceneDefinitions[key].label;
            }

            return answerLabels[group] && answerLabels[group][key] ? answerLabels[group][key] : key;
        }

        function showEndScreen() {
            var answers = deriveAnswers();
            var labels = [
                labelForAnswer('primary_use', answers.primary_use),
                labelForAnswer('form_factor', answers.form_factor),
                labelForAnswer('priority', answers.priority),
                labelForAnswer('budget', answers.budget)
            ];

            fillHiddenAnswers(answers);

            if (endTitle) {
                endTitle.textContent = 'Game đã xong. Profile tai nghe của bạn đã hiện ra.';
            }

            if (endCopy) {
                endCopy.textContent = 'Bạn ghi được ' + state.score + ' điểm và nghiêng về gu: ' + labels.join(' | ') + '.';
            }

            if (endTags) {
                endTags.innerHTML = labels.map(function (label) {
                    return '<span>' + label + '</span>';
                }).join('');
            }

            endScreen.classList.add('is-active');
            playTone('end');
        }

        function finishGame() {
            state.running = false;
            state.finished = true;
            clearItems();
            showEndScreen();
        }

        function loop(timestamp) {
            if (!state.running) {
                return;
            }

            if (!state.lastFrameAt) {
                state.lastFrameAt = timestamp;
            }

            var delta = (timestamp - state.lastFrameAt) / 1000;
            state.lastFrameAt = timestamp;
            state.remaining -= delta;

            if (state.remaining <= 0) {
                updateHud();
                finishGame();
                return;
            }

            var nextWave = currentWaveIndex();
            if (nextWave !== state.waveIndex) {
                state.waveIndex = nextWave;
                updateHud();
                playTone('wave');
            }

            if (state.moveLeft) {
                state.playerX = Math.max(7, state.playerX - (delta * 42));
            }

            if (state.moveRight) {
                state.playerX = Math.min(93, state.playerX + (delta * 42));
            }

            setPlayerPosition();

            if ((timestamp - state.lastSpawnAt) > 520) {
                createItem(randomItemType());
                state.lastSpawnAt = timestamp;
            }

            var playerRect = player.getBoundingClientRect();

            for (var index = state.items.length - 1; index >= 0; index -= 1) {
                var item = state.items[index];
                item.y += item.speed * delta;
                item.node.style.top = item.y + 'px';

                if (item.y > (stage.clientHeight + 40)) {
                    removeItem(item, index);
                    continue;
                }

                var itemRect = item.node.getBoundingClientRect();
                if (intersects(itemRect, playerRect)) {
                    collectItem(item, index);
                }
            }

            updateHud();
            window.requestAnimationFrame(loop);
        }

        function resetGame() {
            state.playerX = 50;
            state.moveLeft = false;
            state.moveRight = false;
            state.running = false;
            state.finished = false;
            state.score = 0;
            state.remaining = state.duration;
            state.lastFrameAt = 0;
            state.lastSpawnAt = 0;
            state.waveIndex = 0;
            resetStats();
            clearItems();
            setPlayerPosition();
            updateHud();
            endScreen.classList.remove('is-active');
            startScreen.classList.add('is-active');
            refreshSceneSelectionUi();
        }

        function startGame() {
            if (!state.selectedScene) {
                refreshSceneSelectionUi();
                return;
            }

            resetGame();
            startScreen.classList.remove('is-active');
            state.running = true;
            state.lastSpawnAt = performance.now();
            stage.focus();
            playTone('start');
            window.requestAnimationFrame(loop);
        }

        function setMove(direction, active) {
            if (direction === 'left') {
                state.moveLeft = active;
            }

            if (direction === 'right') {
                state.moveRight = active;
            }
        }

        document.addEventListener('keydown', function (event) {
            if (event.key === 'ArrowLeft' || event.key === 'a' || event.key === 'A') {
                event.preventDefault();
                setMove('left', true);
            }

            if (event.key === 'ArrowRight' || event.key === 'd' || event.key === 'D') {
                event.preventDefault();
                setMove('right', true);
            }
        });

        document.addEventListener('keyup', function (event) {
            if (event.key === 'ArrowLeft' || event.key === 'a' || event.key === 'A') {
                event.preventDefault();
                setMove('left', false);
            }

            if (event.key === 'ArrowRight' || event.key === 'd' || event.key === 'D') {
                event.preventDefault();
                setMove('right', false);
            }
        });

        stage.addEventListener('mousedown', function (event) {
            if (!state.running) {
                return;
            }

            setPlayerFromClientX(event.clientX);
            stage.focus();
        });

        stage.addEventListener('mousemove', function (event) {
            if (!state.running) {
                return;
            }

            setPlayerFromClientX(event.clientX);
        });

        stage.addEventListener('click', function (event) {
            if (!state.running) {
                return;
            }

            setPlayerFromClientX(event.clientX);
        });

        stage.addEventListener('touchstart', function (event) {
            if (!state.running || !event.touches || !event.touches.length) {
                return;
            }

            event.preventDefault();
            setPlayerFromClientX(event.touches[0].clientX);
        }, { passive: false });

        stage.addEventListener('touchmove', function (event) {
            if (!state.running || !event.touches || !event.touches.length) {
                return;
            }

            event.preventDefault();
            setPlayerFromClientX(event.touches[0].clientX);
        }, { passive: false });

        window.addEventListener('blur', function () {
            setMove('left', false);
            setMove('right', false);
        });

        controlButtons.forEach(function (button) {
            var direction = button.getAttribute('data-control');
            ['mousedown', 'touchstart'].forEach(function (eventName) {
                button.addEventListener(eventName, function (event) {
                    event.preventDefault();
                    setMove(direction, true);
                });
            });

            ['mouseup', 'mouseleave', 'touchend', 'touchcancel'].forEach(function (eventName) {
                button.addEventListener(eventName, function () {
                    setMove(direction, false);
                });
            });
        });

        if (soundButton) {
            try {
                state.soundEnabled = localStorage.getItem('headphone-game-sound') !== 'off';
            } catch (error) {
                state.soundEnabled = true;
            }

            updateSoundButton();

            soundButton.addEventListener('click', function () {
                state.soundEnabled = !state.soundEnabled;
                updateSoundButton();

                try {
                    localStorage.setItem('headphone-game-sound', state.soundEnabled ? 'on' : 'off');
                } catch (error) {
                }

                if (state.soundEnabled) {
                    playTone('wave');
                }
            });
        }

        sceneButtons.forEach(function (button) {
            button.addEventListener('click', function () {
                state.selectedScene = button.getAttribute('data-scene-choice');
                refreshSceneSelectionUi();
                updateHud();
            });
        });

        if (startButton) {
            startButton.addEventListener('click', function () {
                startGame();
            });
        }

        if (restartButton) {
            restartButton.addEventListener('click', function () {
                resetGame();
            });
        }

        if (submitButton) {
            submitButton.addEventListener('click', function () {
                form.submit();
            });
        }

        resetGame();

        if (resultSection) {
            setTimeout(function () {
                resultSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }, 180);
        }
    })();
</script>
@endpush

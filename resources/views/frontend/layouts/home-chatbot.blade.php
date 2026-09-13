<div class="home-chatbot" id="homeChatbot" data-endpoint="{{ route('chatbot.reply') }}">
    <button
        class="home-chatbot__toggle"
        id="homeChatbotToggle"
        type="button"
        aria-expanded="false"
        aria-controls="homeChatbotPanel"
    >
        <span class="home-chatbot__toggle-icon">
            <i class="fa fa-comments" aria-hidden="true"></i>
        </span>
        <span class="home-chatbot__toggle-copy">
            <strong>Robot tư vấn</strong>
            <small>Mở trợ lý AI mua sắm</small>
        </span>
    </button>

    <section class="home-chatbot__panel" id="homeChatbotPanel" hidden aria-label="Chatbot tư vấn">
        <div class="home-chatbot__panel-controls">
            <button
                class="home-chatbot__control home-chatbot__control--compact"
                id="homeChatbotIntroToggle"
                type="button"
                aria-controls="homeChatbotIntro"
                aria-expanded="true"
                aria-label="Ẩn phần giới thiệu"
                title="Ẩn phần giới thiệu"
            >
                <i class="fa fa-minus" aria-hidden="true"></i>
            </button>
            <button class="home-chatbot__control home-chatbot__close" id="homeChatbotClose" type="button" aria-label="Đóng chatbot">
                <i class="fa fa-times" aria-hidden="true"></i>
            </button>
        </div>

        <div class="home-chatbot__intro" id="homeChatbotIntro">
            <div class="home-chatbot__header">
                <div class="home-chatbot__header-copy">
                    <p class="home-chatbot__eyebrow">Trợ lý AI mua sắm</p>
                    <h3>Robot tư vấn sản phẩm</h3>
                    <p class="home-chatbot__header-desc">Lọc theo ngân sách, nhu cầu và thương hiệu trong một khung chat mở rộng giống bảng điều khiển trợ lý ảo.</p>
                    <div class="home-chatbot__header-tags">
                        <span>AI CORE</span>
                        <span>ROBOT MODE</span>
                        <span>LIVE MATCH</span>
                    </div>
                </div>
                <div class="home-chatbot__robot" aria-hidden="true">
                    <div class="home-chatbot__robot-core"></div>
                    <div class="home-chatbot__robot-eyes">
                        <span></span>
                        <span></span>
                    </div>
                    <div class="home-chatbot__robot-mouth"></div>
                </div>
            </div>

            <div class="home-chatbot__status">
                <div class="home-chatbot__status-lead">
                    <span class="home-chatbot__dot"></span>
                    <span>Robot đang online. Bạn có thể nhắn để lọc sâu sản phẩm, xem khuyến mãi, tình trạng hàng và đơn hàng.</span>
                </div>
                <div class="home-chatbot__status-grid">
                    <div class="home-chatbot__status-card">
                        <strong>Ngân sách</strong>
                        <small>Dưới 1 triệu đến phân khúc cao cấp</small>
                    </div>
                    <div class="home-chatbot__status-card">
                        <strong>Nhu cầu</strong>
                        <small>Bluetooth, chống ồn, chơi game, làm việc</small>
                    </div>
                    <div class="home-chatbot__status-card">
                        <strong>Hành động</strong>
                        <small>Tư vấn nhanh, liên hệ shop và tra đơn</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="home-chatbot__substatus">
            <span class="home-chatbot__substatus-pill">AI CORE</span>
            <span class="home-chatbot__substatus-text" id="homeChatbotStateText">Neural match engine đang sẵn sàng phân tích nhu cầu.</span>
        </div>

        <div class="home-chatbot__messages" id="homeChatbotMessages"></div>
        <div class="home-chatbot__quick-actions-heading">
            <span>Tác vụ gợi ý</span>
            <small>Nhấp để gửi lệnh nhanh cho robot</small>
        </div>
        <div class="home-chatbot__quick-actions" id="homeChatbotQuickActions"></div>

        <div class="home-chatbot__composer-bar">
            <span class="home-chatbot__composer-label">AI COMMAND</span>
            <span class="home-chatbot__composer-copy">Nhắn tiêu chí như một lệnh lọc nhanh để robot xử lý.</span>
        </div>
        <form class="home-chatbot__composer" id="homeChatbotForm">
            <input
                class="home-chatbot__input"
                id="homeChatbotInput"
                type="text"
                autocomplete="off"
                placeholder="Ví dụ: tai nghe bluetooth dưới 2 triệu, Sony, chơi game..."
            >
            <button class="home-chatbot__send" id="homeChatbotSend" type="submit" aria-label="Gửi tin nhắn">
                <i class="fa fa-paper-plane" aria-hidden="true"></i>
            </button>
        </form>
    </section>
</div>

@push('styles')
    <style>
        .home-chatbot {
            --chatbot-shell: #07111f;
            --chatbot-shell-soft: #12243b;
            --chatbot-cyan: #7dd3fc;
            --chatbot-cyan-strong: #22d3ee;
            --chatbot-orange: #f7941d;
            --chatbot-orange-deep: #ea580c;
            --chatbot-border: rgba(125, 211, 252, 0.18);
            --chatbot-paper: #fffdf8;
            position: fixed;
            right: 24px;
            bottom: 24px;
            z-index: 2147483000;
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 0;
        }

        .home-chatbot__toggle {
            border: 0;
            border-radius: 999px;
            background:
                radial-gradient(circle at top left, rgba(125, 211, 252, 0.22), transparent 45%),
                linear-gradient(135deg, #07111f, #12243b 54%, #1f3b5b 100%);
            color: #fff;
            display: inline-flex;
            align-items: center;
            gap: 14px;
            padding: 16px 20px;
            box-shadow: 0 18px 45px rgba(7, 17, 31, 0.38);
            border: 1px solid rgba(125, 211, 252, 0.18);
            transition: transform 0.24s ease, box-shadow 0.24s ease;
        }

        .home-chatbot--open .home-chatbot__toggle {
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
            transform: translateY(-14px) scale(0.94);
        }

        .home-chatbot__toggle:hover {
            transform: translateY(-3px);
            box-shadow: 0 24px 54px rgba(7, 17, 31, 0.45);
        }

        .home-chatbot__toggle-icon {
            width: 46px;
            height: 46px;
            border-radius: 16px;
            background:
                radial-gradient(circle at 30% 30%, rgba(125, 211, 252, 0.6), transparent 40%),
                linear-gradient(160deg, rgba(34, 211, 238, 0.28), rgba(247, 148, 29, 0.24));
            border: 1px solid rgba(125, 211, 252, 0.25);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.12);
        }

        .home-chatbot__toggle-copy {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            line-height: 1.2;
        }

        .home-chatbot__toggle-copy strong {
            font-size: 15px;
            letter-spacing: 0.02em;
        }

        .home-chatbot__toggle-copy small {
            color: rgba(226, 232, 240, 0.74);
        }

        .home-chatbot__panel {
            position: fixed;
            top: 18px;
            right: 0;
            bottom: 18px;
            width: min(720px, 48vw);
            max-height: calc(100vh - 36px);
            background: linear-gradient(180deg, #07111f 0%, #12243b 30%, #f7fbff 30%, #fffdf8 100%);
            border-radius: 38px 0 0 38px;
            overflow: hidden;
            box-shadow: 0 30px 90px rgba(7, 17, 31, 0.45);
            display: flex;
            flex-direction: column;
            border: 1px solid var(--chatbot-border);
            border-right: 0;
        }

        .home-chatbot__panel::before {
            content: '';
            position: absolute;
            inset: 0 0 auto 0;
            height: 220px;
            background:
                radial-gradient(circle at top left, rgba(125, 211, 252, 0.22), transparent 42%),
                radial-gradient(circle at top right, rgba(247, 148, 29, 0.18), transparent 32%),
                repeating-linear-gradient(
                    90deg,
                    rgba(125, 211, 252, 0.08) 0,
                    rgba(125, 211, 252, 0.08) 1px,
                    transparent 1px,
                    transparent 32px
                ),
                repeating-linear-gradient(
                    180deg,
                    rgba(125, 211, 252, 0.06) 0,
                    rgba(125, 211, 252, 0.06) 1px,
                    transparent 1px,
                    transparent 28px
                );
            pointer-events: none;
            opacity: 0.9;
        }

        .home-chatbot__panel-controls,
        .home-chatbot__intro {
            position: relative;
            z-index: 1;
        }

        .home-chatbot__panel-controls {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            padding: 18px 18px 0;
        }

        .home-chatbot__control {
            width: 42px;
            height: 42px;
            border: 0;
            border-radius: 14px;
            background: rgba(255, 255, 255, 0.08);
            color: #fff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex: 0 0 auto;
            border: 1px solid rgba(125, 211, 252, 0.12);
            transition: transform 0.2s ease, background 0.2s ease, border-color 0.2s ease;
        }

        .home-chatbot__control:hover {
            transform: translateY(-1px);
            background: rgba(255, 255, 255, 0.14);
            border-color: rgba(125, 211, 252, 0.22);
        }

        .home-chatbot__intro {
            max-height: 520px;
            overflow: hidden;
            opacity: 1;
            transition: max-height 0.28s ease, opacity 0.22s ease, transform 0.28s ease;
        }

        .home-chatbot--intro-hidden .home-chatbot__intro {
            max-height: 0;
            opacity: 0;
            transform: translateY(-12px);
            pointer-events: none;
        }

        .home-chatbot__header,
        .home-chatbot__status {
            position: relative;
            z-index: 1;
        }

        .home-chatbot__header {
            color: #fff;
            padding: 8px 24px 20px;
            display: grid;
            grid-template-columns: minmax(0, 1fr) 120px;
            gap: 18px;
            align-items: center;
        }

        .home-chatbot__header-copy {
            min-width: 0;
        }

        .home-chatbot__eyebrow {
            margin: 0 0 6px;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.18em;
            color: rgba(186, 230, 253, 0.68);
        }

        .home-chatbot__header h3 {
            margin: 0;
            font-size: 28px;
            line-height: 1.05;
            color: #fff;
            letter-spacing: -0.03em;
        }

        .home-chatbot__header-desc {
            margin: 10px 0 0;
            max-width: 340px;
            font-size: 13px;
            line-height: 1.65;
            color: rgba(226, 232, 240, 0.78);
        }

        .home-chatbot__header-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 14px;
        }

        .home-chatbot__header-tags span {
            padding: 7px 10px;
            border-radius: 999px;
            background: rgba(125, 211, 252, 0.08);
            border: 1px solid rgba(125, 211, 252, 0.18);
            color: #d9f8ff;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 0.14em;
            text-transform: uppercase;
        }

        .home-chatbot__robot {
            width: 120px;
            height: 120px;
            border-radius: 28px;
            position: relative;
            justify-self: end;
            background:
                radial-gradient(circle at 50% 25%, rgba(125, 211, 252, 0.4), transparent 32%),
                linear-gradient(160deg, rgba(34, 211, 238, 0.18), rgba(7, 17, 31, 0.2));
            border: 1px solid rgba(125, 211, 252, 0.25);
            box-shadow:
                inset 0 1px 0 rgba(255, 255, 255, 0.08),
                0 16px 36px rgba(2, 12, 27, 0.28);
            animation: home-chatbot-robot-glow 3.8s ease-in-out infinite;
        }

        .home-chatbot__robot::before,
        .home-chatbot__robot::after {
            content: '';
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            border-radius: 999px;
            border: 1px solid rgba(125, 211, 252, 0.18);
        }

        .home-chatbot__robot::before {
            top: 14px;
            width: 62px;
            height: 10px;
            background: rgba(125, 211, 252, 0.14);
        }

        .home-chatbot__robot::after {
            top: 22px;
            width: 16px;
            height: 16px;
            background: radial-gradient(circle, var(--chatbot-orange) 0%, rgba(247, 148, 29, 0.12) 72%);
            box-shadow: 0 0 14px rgba(247, 148, 29, 0.42);
        }

        .home-chatbot__robot-core {
            position: absolute;
            inset: 34px 28px 24px;
            border-radius: 24px;
            background:
                linear-gradient(180deg, rgba(15, 23, 42, 0.72), rgba(8, 47, 73, 0.84)),
                linear-gradient(135deg, rgba(34, 211, 238, 0.2), rgba(247, 148, 29, 0.12));
            border: 1px solid rgba(125, 211, 252, 0.22);
        }

        .home-chatbot__robot-eyes {
            position: absolute;
            top: 54px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 16px;
        }

        .home-chatbot__robot-eyes span {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: radial-gradient(circle, #d9f8ff 0%, var(--chatbot-cyan-strong) 48%, rgba(34, 211, 238, 0.12) 100%);
            box-shadow: 0 0 16px rgba(34, 211, 238, 0.55);
        }

        .home-chatbot__robot-mouth {
            position: absolute;
            left: 50%;
            bottom: 31px;
            width: 42px;
            height: 14px;
            transform: translateX(-50%);
            border-radius: 0 0 999px 999px;
            border: 2px solid rgba(125, 211, 252, 0.72);
            border-top: 0;
        }

        .home-chatbot__close {
            background: rgba(255, 255, 255, 0.1);
        }

        .home-chatbot__status {
            display: grid;
            gap: 12px;
            padding: 0 24px 18px;
            border-bottom: 1px solid rgba(226, 232, 240, 0.6);
        }

        .home-chatbot__status-lead {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 14px 16px;
            border-radius: 18px;
            background: rgba(7, 17, 31, 0.78);
            color: #d9f8ff;
            font-size: 13px;
            border: 1px solid rgba(125, 211, 252, 0.18);
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.05);
        }

        .home-chatbot__status-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 10px;
        }

        .home-chatbot__status-card {
            padding: 13px 14px;
            border-radius: 18px;
            background: rgba(255, 255, 255, 0.92);
            border: 1px solid rgba(203, 213, 225, 0.95);
            box-shadow: 0 10px 24px rgba(148, 163, 184, 0.12);
        }

        .home-chatbot__status-card strong,
        .home-chatbot__status-card small {
            display: block;
        }

        .home-chatbot__status-card strong {
            margin-bottom: 6px;
            color: #0f172a;
            font-size: 13px;
        }

        .home-chatbot__status-card small {
            color: #475569;
            line-height: 1.5;
        }

        .home-chatbot__substatus {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 14px 24px 12px;
            background: linear-gradient(180deg, rgba(8, 16, 30, 0.08) 0%, rgba(255, 255, 255, 0) 100%);
        }

        .home-chatbot__substatus-pill {
            padding: 7px 11px;
            border-radius: 999px;
            background: rgba(7, 17, 31, 0.92);
            color: #8de8ff;
            border: 1px solid rgba(125, 211, 252, 0.22);
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 0.16em;
            text-transform: uppercase;
            box-shadow: 0 10px 24px rgba(7, 17, 31, 0.15);
        }

        .home-chatbot__substatus-text {
            color: #0f3b59;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 0.01em;
        }

        .home-chatbot__dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: #22c55e;
            box-shadow: 0 0 0 7px rgba(34, 197, 94, 0.16);
            flex: 0 0 auto;
        }

        .home-chatbot__messages {
            padding: 22px 24px 14px;
            overflow-y: auto;
            background:
                linear-gradient(180deg, rgba(248, 251, 255, 0.98) 0%, rgba(255, 247, 237, 0.98) 100%);
            flex: 1 1 auto;
            min-height: 0;
            transition: padding-top 0.22s ease;
        }

        .home-chatbot--intro-hidden .home-chatbot__messages {
            padding-top: 8px;
        }

        .home-chatbot__message {
            display: flex;
            margin-bottom: 14px;
        }

        .home-chatbot__message--bot {
            justify-content: flex-start;
        }

        .home-chatbot__message--user {
            justify-content: flex-end;
        }

        .home-chatbot__message-frame {
            display: flex;
            align-items: flex-end;
            gap: 10px;
            max-width: 92%;
        }

        .home-chatbot__message--user .home-chatbot__message-frame {
            flex-direction: row-reverse;
        }

        .home-chatbot__avatar {
            width: 38px;
            height: 38px;
            border-radius: 14px;
            flex: 0 0 auto;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 0.14em;
            text-transform: uppercase;
        }

        .home-chatbot__avatar--bot {
            background:
                radial-gradient(circle at 30% 30%, rgba(125, 211, 252, 0.55), transparent 38%),
                linear-gradient(145deg, #07111f, #1d4c6e);
            color: #d9f8ff;
            border: 1px solid rgba(125, 211, 252, 0.24);
            box-shadow: 0 12px 24px rgba(7, 17, 31, 0.18);
        }

        .home-chatbot__avatar--user {
            background: linear-gradient(145deg, #f7941d, #ea580c);
            color: #fff;
            border: 1px solid rgba(255, 255, 255, 0.24);
            box-shadow: 0 12px 24px rgba(234, 88, 12, 0.18);
        }

        .home-chatbot__message-stack {
            display: flex;
            flex-direction: column;
            gap: 6px;
            min-width: 0;
        }

        .home-chatbot__message-meta {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: #0f3b59;
        }

        .home-chatbot__message-meta::after {
            content: '';
            width: 24px;
            height: 1px;
            background: rgba(15, 59, 89, 0.22);
        }

        .home-chatbot__message--user .home-chatbot__message-meta {
            justify-content: flex-end;
            color: rgba(15, 23, 42, 0.72);
        }

        .home-chatbot__bubble {
            max-width: 88%;
            border-radius: 22px;
            padding: 14px 16px;
            font-size: 14px;
            line-height: 1.6;
            position: relative;
        }

        .home-chatbot__message--bot .home-chatbot__bubble {
            background: rgba(255, 255, 255, 0.94);
            color: #0f172a;
            border: 1px solid rgba(125, 211, 252, 0.26);
            box-shadow: 0 14px 28px rgba(148, 163, 184, 0.16);
        }

        .home-chatbot__message--bot .home-chatbot__bubble::before {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: inherit;
            background:
                linear-gradient(135deg, rgba(125, 211, 252, 0.08), transparent 36%),
                repeating-linear-gradient(
                    90deg,
                    rgba(125, 211, 252, 0.04) 0,
                    rgba(125, 211, 252, 0.04) 1px,
                    transparent 1px,
                    transparent 22px
                );
            pointer-events: none;
        }

        .home-chatbot__message--user .home-chatbot__bubble {
            background: linear-gradient(135deg, #f7941d, #ea580c);
            color: #fff;
            box-shadow: 0 14px 28px rgba(234, 88, 12, 0.24);
        }

        .home-chatbot__bubble p:last-child,
        .home-chatbot__bubble ul:last-child {
            margin-bottom: 0;
        }

        .home-chatbot__bubble ul {
            margin: 10px 0 0;
            padding-left: 18px;
        }

        .home-chatbot__bubble a {
            color: #0f766e;
            font-weight: 700;
            text-decoration: underline;
        }

        .home-chatbot__message--user .home-chatbot__bubble a {
            color: #fff;
        }

        .home-chatbot__bubble--loading {
            min-width: 220px;
        }

        .home-chatbot__thinking {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .home-chatbot__thinking-dots {
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .home-chatbot__thinking-dots span {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: #22d3ee;
            box-shadow: 0 0 12px rgba(34, 211, 238, 0.35);
            animation: home-chatbot-thinking 1.05s infinite ease-in-out;
        }

        .home-chatbot__thinking-dots span:nth-child(2) {
            animation-delay: 0.14s;
        }

        .home-chatbot__thinking-dots span:nth-child(3) {
            animation-delay: 0.28s;
        }

        .home-chatbot__thinking-copy strong,
        .home-chatbot__thinking-copy small {
            display: block;
        }

        .home-chatbot__thinking-copy strong {
            color: #0f172a;
            font-size: 12px;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .home-chatbot__thinking-copy small {
            color: #475569;
            line-height: 1.45;
        }

        .home-chatbot__quick-actions-heading {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            padding: 0 24px 10px;
            color: #0f3b59;
        }

        .home-chatbot__quick-actions-heading span {
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 0.18em;
            text-transform: uppercase;
        }

        .home-chatbot__quick-actions-heading small {
            color: #64748b;
            font-size: 12px;
        }

        .home-chatbot__quick-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            padding: 0 24px 16px;
            background: linear-gradient(180deg, rgba(255, 255, 255, 0) 0%, rgba(255, 253, 248, 0.94) 58%);
        }

        .home-chatbot__chip {
            border: 1px solid rgba(125, 211, 252, 0.28);
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.96);
            color: #0f172a;
            font-size: 12px;
            font-weight: 700;
            padding: 9px 13px;
            box-shadow: 0 8px 18px rgba(148, 163, 184, 0.12);
            transition: background 0.2s ease, transform 0.2s ease, border-color 0.2s ease;
        }

        .home-chatbot__chip:hover {
            background: #f0f9ff;
            border-color: rgba(34, 211, 238, 0.46);
            transform: translateY(-1px);
        }

        .home-chatbot__chip:disabled {
            opacity: 0.55;
            cursor: not-allowed;
            transform: none;
        }

        .home-chatbot__composer {
            border-top: 1px solid rgba(226, 232, 240, 0.9);
            padding: 16px 18px 18px;
            background: rgba(255, 255, 255, 0.94);
            backdrop-filter: blur(12px);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .home-chatbot__composer-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            padding: 10px 18px 0;
            background: rgba(255, 255, 255, 0.94);
        }

        .home-chatbot__composer-label {
            color: #0f3b59;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 0.18em;
            text-transform: uppercase;
        }

        .home-chatbot__composer-copy {
            color: #64748b;
            font-size: 12px;
            text-align: right;
        }

        .home-chatbot__input {
            flex: 1 1 auto;
            height: 54px;
            border-radius: 18px;
            border: 1px solid rgba(148, 163, 184, 0.4);
            padding: 0 18px;
            font-size: 14px;
            color: #0f172a;
            background: #fff;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.85);
        }

        .home-chatbot__input:focus {
            outline: none;
            border-color: rgba(34, 211, 238, 0.62);
            box-shadow: 0 0 0 4px rgba(34, 211, 238, 0.14);
        }

        .home-chatbot__send {
            width: 54px;
            height: 54px;
            border: 0;
            border-radius: 18px;
            background: linear-gradient(145deg, #07111f, #22d3ee);
            color: #fff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 16px 32px rgba(15, 23, 42, 0.28);
        }

        .home-chatbot__send:disabled,
        .home-chatbot__input:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }

        @keyframes home-chatbot-robot-glow {
            0%,
            100% {
                transform: translateY(0);
                box-shadow:
                    inset 0 1px 0 rgba(255, 255, 255, 0.08),
                    0 16px 36px rgba(2, 12, 27, 0.28);
            }

            50% {
                transform: translateY(-3px);
                box-shadow:
                    inset 0 1px 0 rgba(255, 255, 255, 0.08),
                    0 24px 40px rgba(34, 211, 238, 0.14);
            }
        }

        @keyframes home-chatbot-thinking {
            0%,
            80%,
            100% {
                transform: scale(0.65);
                opacity: 0.45;
            }

            40% {
                transform: scale(1);
                opacity: 1;
            }
        }

        @media (max-width: 1199.98px) {
            .home-chatbot__panel {
                width: min(620px, 54vw);
            }
        }

        @media (max-width: 991.98px) {
            .home-chatbot {
                right: 12px;
                bottom: 12px;
            }

            .home-chatbot__panel {
                top: 14px;
                right: 12px;
                bottom: 14px;
                width: min(100vw - 24px, 560px);
                max-height: calc(100vh - 28px);
                border-radius: 28px;
                border-right: 1px solid var(--chatbot-border);
            }
        }

        @media (max-width: 767.98px) {
            .home-chatbot {
                right: 12px;
            }

            .home-chatbot__panel {
                width: min(100vw - 24px, 500px);
                max-height: calc(100vh - 24px);
            }

            .home-chatbot__header {
                grid-template-columns: minmax(0, 1fr) 96px;
                gap: 14px;
            }

            .home-chatbot__header h3 {
                font-size: 24px;
            }

            .home-chatbot__header-desc {
                max-width: 100%;
            }

            .home-chatbot__robot {
                width: 96px;
                height: 96px;
                border-radius: 22px;
            }

            .home-chatbot__robot-core {
                inset: 28px 22px 20px;
            }

            .home-chatbot__robot-eyes {
                top: 44px;
            }

            .home-chatbot__robot-mouth {
                bottom: 24px;
            }

            .home-chatbot__status-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .home-chatbot__quick-actions-heading,
            .home-chatbot__composer-bar {
                flex-direction: column;
                align-items: flex-start;
            }

            .home-chatbot__composer-copy {
                text-align: left;
            }
        }

        @media (max-width: 575.98px) {
            .home-chatbot {
                right: 12px;
                left: 12px;
                bottom: 12px;
                align-items: stretch;
            }

            .home-chatbot__toggle {
                width: 100%;
                justify-content: center;
            }

            .home-chatbot__panel {
                width: 100%;
                top: 12px;
                right: 12px;
                bottom: 12px;
                max-height: calc(100vh - 24px);
                border-radius: 24px;
            }

            .home-chatbot__header {
                padding: 20px 18px 18px;
                grid-template-columns: minmax(0, 1fr) 84px;
            }

            .home-chatbot__header h3 {
                font-size: 21px;
            }

            .home-chatbot__header-desc {
                font-size: 12px;
            }

            .home-chatbot__status,
            .home-chatbot__messages,
            .home-chatbot__quick-actions,
            .home-chatbot__quick-actions-heading {
                padding-left: 18px;
                padding-right: 18px;
            }

            .home-chatbot__panel-controls {
                padding: 14px 14px 0;
            }

            .home-chatbot__status-grid {
                grid-template-columns: 1fr;
            }

            .home-chatbot__composer {
                padding: 14px 14px 16px;
            }

            .home-chatbot__composer-bar {
                padding: 10px 14px 0;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var chatbotRoot = document.getElementById('homeChatbot');

            if (!chatbotRoot || typeof window.jQuery === 'undefined') {
                return;
            }

            var endpoint = chatbotRoot.getAttribute('data-endpoint');
            var csrfTokenMeta = document.querySelector('meta[name="csrf-token"]');
            var csrfToken = csrfTokenMeta ? csrfTokenMeta.getAttribute('content') : '';
            var toggleButton = document.getElementById('homeChatbotToggle');
            var closeButton = document.getElementById('homeChatbotClose');
            var introToggleButton = document.getElementById('homeChatbotIntroToggle');
            var panel = document.getElementById('homeChatbotPanel');
            var messages = document.getElementById('homeChatbotMessages');
            var stateText = document.getElementById('homeChatbotStateText');
            var quickActions = document.getElementById('homeChatbotQuickActions');
            var form = document.getElementById('homeChatbotForm');
            var input = document.getElementById('homeChatbotInput');
            var sendButton = document.getElementById('homeChatbotSend');
            var introStorageKey = 'homeChatbotIntroHidden';
            var isInitialized = false;

            var defaultActions = [
                { label: 'Dưới 1 triệu', value: 'tai nghe dưới 1 triệu' },
                { label: 'Bluetooth', value: 'tai nghe bluetooth' },
                { label: 'Chống ồn', value: 'tai nghe chống ồn' },
                { label: 'Chơi game', value: 'tai nghe chơi game' },
                { label: 'Liên hệ', value: 'liên hệ shop' }
            ];

            function escapeHtml(value) {
                return String(value || '')
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/"/g, '&quot;')
                    .replace(/'/g, '&#39;');
            }

            function scrollToBottom() {
                messages.scrollTop = messages.scrollHeight;
            }

            function setAssistantState(text) {
                if (stateText) {
                    stateText.textContent = text;
                }
            }

            function readIntroHiddenPreference() {
                try {
                    return window.localStorage.getItem(introStorageKey) === '1';
                } catch (error) {
                    return false;
                }
            }

            function writeIntroHiddenPreference(isHidden) {
                try {
                    window.localStorage.setItem(introStorageKey, isHidden ? '1' : '0');
                } catch (error) {
                    // Ignore storage errors and keep the UI usable.
                }
            }

            function setIntroHidden(isHidden) {
                var toggleLabel = isHidden ? 'Hiện phần giới thiệu' : 'Ẩn phần giới thiệu';
                var icon = introToggleButton ? introToggleButton.querySelector('i') : null;

                chatbotRoot.classList.toggle('home-chatbot--intro-hidden', isHidden);

                if (introToggleButton) {
                    introToggleButton.setAttribute('aria-expanded', isHidden ? 'false' : 'true');
                    introToggleButton.setAttribute('aria-label', toggleLabel);
                    introToggleButton.setAttribute('title', toggleLabel);
                }

                if (icon) {
                    icon.className = isHidden ? 'fa fa-plus' : 'fa fa-minus';
                }

                writeIntroHiddenPreference(isHidden);
            }

            function appendMessage(role, html, options) {
                var settings = options || {};
                var wrapper = document.createElement('div');
                wrapper.className = 'home-chatbot__message home-chatbot__message--' + role;

                var frame = document.createElement('div');
                frame.className = 'home-chatbot__message-frame';

                var avatar = document.createElement('div');
                avatar.className = 'home-chatbot__avatar home-chatbot__avatar--' + role;
                avatar.textContent = role === 'bot' ? 'AI' : 'YOU';

                var stack = document.createElement('div');
                stack.className = 'home-chatbot__message-stack';

                var meta = document.createElement('div');
                meta.className = 'home-chatbot__message-meta';
                meta.textContent = settings.meta || (role === 'bot' ? 'AI CORE' : 'Người dùng');

                var bubble = document.createElement('div');
                bubble.className = 'home-chatbot__bubble';

                if (settings.isLoading) {
                    bubble.className += ' home-chatbot__bubble--loading';
                }

                bubble.innerHTML = html;

                stack.appendChild(meta);
                stack.appendChild(bubble);
                frame.appendChild(avatar);
                frame.appendChild(stack);
                wrapper.appendChild(frame);
                messages.appendChild(wrapper);
                scrollToBottom();

                return wrapper;
            }

            function appendLoadingMessage() {
                return appendMessage(
                    'bot',
                    '<div class="home-chatbot__thinking"><span class="home-chatbot__thinking-dots"><span></span><span></span><span></span></span><span class="home-chatbot__thinking-copy"><strong>AI CORE đang suy luận</strong><small>Quét dữ liệu sản phẩm và ghép nhu cầu phù hợp cho bạn.</small></span></div>',
                    { meta: 'AI CORE • PROCESSING', isLoading: true }
                );
            }

            function setQuickActions(actions) {
                quickActions.innerHTML = '';

                actions.forEach(function (action) {
                    var button = document.createElement('button');
                    button.type = 'button';
                    button.className = 'home-chatbot__chip';
                    button.textContent = action.label;
                    button.addEventListener('click', function () {
                        handleUserMessage(action.value || action.label);
                    });
                    quickActions.appendChild(button);
                });
            }

            function setBusyState(isBusy) {
                input.disabled = isBusy;
                sendButton.disabled = isBusy;

                Array.prototype.forEach.call(quickActions.querySelectorAll('button'), function (button) {
                    button.disabled = isBusy;
                });
            }

            function requestReply(message) {
                var loadingMessage = appendLoadingMessage();
                setBusyState(true);
                setAssistantState('AI CORE đang quét dữ liệu và suy luận theo yêu cầu vừa nhập.');

                window.jQuery.ajax({
                    url: endpoint,
                    method: 'POST',
                    dataType: 'json',
                    headers: csrfToken ? { 'X-CSRF-TOKEN': csrfToken } : {},
                    data: { message: message, _token: csrfToken }
                }).done(function (response) {
                    loadingMessage.remove();
                    appendMessage('bot', response.html || '<p>Mình chưa tìm thấy thông tin phù hợp. Bạn thử diễn đạt cách khác nhé.</p>', {
                        meta: 'AI CORE • RESPONSE'
                    });
                    setQuickActions((response.actions && response.actions.length) ? response.actions : defaultActions);
                    setAssistantState('Robot đã sẵn sàng phản hồi. Bạn có thể tiếp tục lọc sâu hơn bằng một lệnh khác.');
                }).fail(function () {
                    loadingMessage.remove();
                    appendMessage('bot', '<p>Không thể kết nối chatbot lúc này. Bạn có thể thử lại hoặc mở <a href="{{ route('contact') }}">trang liên hệ</a>.</p>', {
                        meta: 'AI CORE • ERROR'
                    });
                    setQuickActions(defaultActions);
                    setAssistantState('AI CORE đang gặp lỗi kết nối. Bạn có thể thử lại sau vài giây.');
                }).always(function () {
                    setBusyState(false);
                    input.focus();
                });
            }

            function handleUserMessage(message) {
                var trimmedMessage = String(message || '').trim();

                if (!trimmedMessage) {
                    input.focus();
                    return;
                }

                appendMessage('user', '<p>' + escapeHtml(trimmedMessage) + '</p>', {
                    meta: 'Người dùng • Command'
                });
                setAssistantState('Lệnh mới đã được gửi tới AI CORE. Đang chuẩn bị suy luận.');
                requestReply(trimmedMessage);
            }

            function openChatbot() {
                chatbotRoot.classList.add('home-chatbot--open');
                panel.hidden = false;
                toggleButton.setAttribute('aria-expanded', 'true');
                setAssistantState('Neural match engine đang sẵn sàng phân tích nhu cầu.');

                if (!isInitialized) {
                    appendMessage('bot', '<p>Xin chào. Mình có thể giúp bạn lọc sản phẩm theo ngân sách, thương hiệu và nhu cầu như Bluetooth, chống ồn, chơi game, làm việc hoặc tình trạng còn hàng.</p><p>Bạn cứ nhắn tự nhiên như: "tai nghe dưới 1 triệu", "Sony chống ồn", "mẫu chơi game" hoặc "còn hàng".</p>', {
                        meta: 'AI CORE • BOOT COMPLETE'
                    });
                    setQuickActions(defaultActions);
                    isInitialized = true;
                }

                window.setTimeout(function () {
                    input.focus();
                    scrollToBottom();
                }, 50);
            }

            function closeChatbot() {
                chatbotRoot.classList.remove('home-chatbot--open');
                panel.hidden = true;
                toggleButton.setAttribute('aria-expanded', 'false');
            }

            setIntroHidden(readIntroHiddenPreference());

            toggleButton.addEventListener('click', function () {
                if (panel.hidden) {
                    openChatbot();
                    return;
                }

                closeChatbot();
            });

            closeButton.addEventListener('click', closeChatbot);

            if (introToggleButton) {
                introToggleButton.addEventListener('click', function () {
                    var willHideIntro = !chatbotRoot.classList.contains('home-chatbot--intro-hidden');

                    setIntroHidden(willHideIntro);

                    window.setTimeout(function () {
                        scrollToBottom();
                        input.focus();
                    }, 80);
                });
            }

            form.addEventListener('submit', function (event) {
                event.preventDefault();
                handleUserMessage(input.value);
                input.value = '';
            });
        });
    </script>
@endpush

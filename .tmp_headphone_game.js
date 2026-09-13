
    (function () {
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
        var tagNodes = {};
        var controlButtons = Array.prototype.slice.call(document.querySelectorAll('[data-control]'));

        if (!stage || !player || !form) {
            return;
        }

        Array.prototype.forEach.call(document.querySelectorAll('[data-game-tag]'), function (node) {
            tagNodes[node.getAttribute('data-game-tag')] = node;
        });

        var itemDefinitions = {
            office: { label: 'Việc', icon: 'fas fa-briefcase', className: 'is-office', points: 12 },
            gaming: { label: 'Game', icon: 'fas fa-gamepad', className: 'is-gaming', points: 12 },
            running: { label: 'Chạy', icon: 'fas fa-bolt', className: 'is-running', points: 12 },
            study: { label: 'Êm', icon: 'fas fa-cloud', className: 'is-study', points: 12 },
            focus: { label: 'Tĩnh', icon: 'fas fa-moon', className: 'is-focus', points: 10 },
            mic: { label: 'Mic', icon: 'fas fa-microphone', className: 'is-mic', points: 10 },
            comfort: { label: 'Êm', icon: 'fas fa-feather-alt', className: 'is-comfort', points: 10 },
            value: { label: 'Hời', icon: 'fas fa-tags', className: 'is-value', points: 10 },
            over_ear: { label: 'Chụp', icon: 'fas fa-headphones', className: 'is-over-ear', points: 9 },
            true_wireless: { label: 'TWS', icon: 'fas fa-broadcast-tower', className: 'is-wireless', points: 9 },
            budget_low: { label: '<1tr', icon: 'fas fa-coins', className: 'is-budget-low', points: 8 },
            budget_mid: { label: '1-2tr', icon: 'fas fa-wallet', className: 'is-budget-mid', points: 8 },
            budget_high: { label: '>3tr', icon: 'fas fa-gem', className: 'is-budget-high', points: 8 },
            noise: { label: 'Ồn', icon: 'fas fa-volume-up', className: 'is-noise', points: -12, bad: true }
        };

        var waves = [
            { limit: 10, label: 'Bối cảnh', pool: ['office', 'gaming', 'running', 'study', 'noise'] },
            { limit: 20, label: 'Ưu tiên', pool: ['focus', 'mic', 'comfort', 'value', 'noise'] },
            { limit: 30, label: 'Form & giá', pool: ['over_ear', 'true_wireless', 'budget_low', 'budget_mid', 'budget_high', 'noise'] }
        ];

        var answerLabels = {
            primary_use: {
                office: 'Làm việc / tập trung',
                running: 'Di chuyển / linh hoạt',
                gaming: 'Gaming / callout',
                study: 'Đeo lâu / học tập'
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
            stats: {}
        };

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

        function updateHud() {
            if (timerNode) {
                timerNode.textContent = Math.max(0, Math.ceil(state.remaining)) + 's';
            }

            if (scoreNode) {
                scoreNode.textContent = state.score;
            }

            if (waveNode) {
                waveNode.textContent = waves[state.waveIndex].label;
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

        function createItem(type) {
            var definition = itemDefinitions[type];
            if (!definition) {
                return;
            }

            var node = document.createElement('div');
            node.className = 'headphone-game-item ' + definition.className;
            node.innerHTML = '<i class="' + definition.icon + '"></i><span>' + definition.label + '</span>';

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
            var pool = waves[state.waveIndex].pool;
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
                primary_use: pickTop(useScores, 'office'),
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

        function showEndScreen() {
            var answers = deriveAnswers();
            var labels = [
                answerLabels.primary_use[answers.primary_use],
                answerLabels.form_factor[answers.form_factor],
                answerLabels.priority[answers.priority],
                answerLabels.budget[answers.budget]
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
        }

        function startGame() {
            resetGame();
            startScreen.classList.remove('is-active');
            state.running = true;
            state.lastSpawnAt = performance.now();
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
                setMove('left', true);
            }

            if (event.key === 'ArrowRight' || event.key === 'd' || event.key === 'D') {
                setMove('right', true);
            }
        });

        document.addEventListener('keyup', function (event) {
            if (event.key === 'ArrowLeft' || event.key === 'a' || event.key === 'A') {
                setMove('left', false);
            }

            if (event.key === 'ArrowRight' || event.key === 'd' || event.key === 'D') {
                setMove('right', false);
            }
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


<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\BuildsListingMedia;
use App\Models\Category;
use App\Models\Post;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class HeadphoneQuizController extends Controller
{
    use BuildsListingMedia;

    public function show()
    {
        return view('frontend.pages.headphone-quiz', $this->buildViewData());
    }

    public function submit(Request $request)
    {
        $experienceMode = $request->input('experience_mode', 'build');

        if ($experienceMode === 'battle') {
            $validated = $request->validate(
                $this->battleValidationRules(),
                $this->battleValidationMessages()
            );

            $battleSelections = $validated['battle_choices'] ?? [];
            $derivedAnswers = $this->deriveAnswersFromBattleChoices($battleSelections);

            return view('frontend.pages.headphone-quiz', $this->buildViewData(
                $derivedAnswers,
                $this->buildRecommendation($derivedAnswers, $experienceMode, $battleSelections),
                $experienceMode,
                $battleSelections
            ));
        }

        $validated = $request->validate(
            $this->buildValidationRules(),
            $this->buildValidationMessages()
        );

        $answers = Arr::only($validated, ['primary_use', 'form_factor', 'priority', 'budget']);

        return view('frontend.pages.headphone-quiz', $this->buildViewData(
            $answers,
            $this->buildRecommendation($answers, 'build'),
            'build'
        ));
    }

    protected function buildViewData(
        array $selectedAnswers = [],
        array $recommendation = [],
        $activeExperienceMode = 'build',
        array $battleSelections = []
    ) {
        return [
            'questions' => $this->questionDefinitions(),
            'selectedAnswers' => $selectedAnswers,
            'recommendation' => $recommendation,
            'experienceModes' => $this->experienceModes(),
            'activeExperienceMode' => $activeExperienceMode,
            'battleRounds' => $this->battleRounds(),
            'battleSelections' => $battleSelections,
        ];
    }

    protected function experienceModes()
    {
        return [
            'build' => [
                'label' => 'Dựng setup',
                'headline' => 'Dựng nhanh bối cảnh trước khi chốt mẫu.',
                'description' => 'Chọn bối cảnh, kiểu đeo, ưu tiên và ngân sách theo cách trực quan hơn quiz cũ.',
            ],
            'battle' => [
                'label' => 'Đấu gu tai nghe',
                'headline' => '4 round đối đầu để lộ ra gu nghe thật.',
                'description' => 'Mỗi round bạn chỉ cần chọn phe thắng, hệ thống sẽ tự suy ra cấu hình tai nghe phù hợp.',
            ],
            'arcade' => [
                'label' => 'Bắt Sóng 45s',
                'headline' => 'Chơi minigame thật để mở hồ sơ tai nghe.',
                'description' => 'Di chuyển, bắt item hợp gu, né tạp âm và để hệ thống suy ra mẫu tai nghe phù hợp.',
            ],
        ];
    }

    protected function buildValidationRules()
    {
        return [
            'experience_mode' => 'required|in:build,battle,arcade',
            'primary_use' => 'required|in:office,running,gaming,study',
            'form_factor' => 'required|in:over_ear,true_wireless,flexible',
            'priority' => 'required|in:noise,mic,comfort,value',
            'budget' => 'required|in:under_1000,between_1000_2000,between_2000_3000,over_3000',
        ];
    }

    protected function buildValidationMessages()
    {
        return [
            'experience_mode.required' => 'Hãy chọn một cách chơi trước khi bắt đầu.',
            'primary_use.required' => 'Hãy chọn bối cảnh bạn dùng tai nghe nhiều nhất.',
            'form_factor.required' => 'Hãy chọn dáng tai nghe bạn nghiêng tới.',
            'priority.required' => 'Hãy chọn ưu tiên lớn nhất của bạn.',
            'budget.required' => 'Hãy chọn tầm ngân sách để shop gợi ý sát hơn.',
        ];
    }

    protected function battleValidationRules()
    {
        $rules = [
            'experience_mode' => 'required|in:build,battle',
        ];

        foreach ($this->battleRounds() as $roundKey => $round) {
            $rules['battle_choices.' . $roundKey] = 'required|in:' . implode(',', array_keys($round['options']));
        }

        return $rules;
    }

    protected function battleValidationMessages()
    {
        $messages = [
            'experience_mode.required' => 'Hãy chọn một cách chơi trước khi bắt đầu.',
        ];

        foreach ($this->battleRounds() as $roundKey => $round) {
            $messages['battle_choices.' . $roundKey . '.required'] = 'Hãy chọn phe thắng cho ' . Str::lower($round['title']) . '.';
            $messages['battle_choices.' . $roundKey . '.in'] = 'Lựa chọn round vừa gửi không hợp lệ. Thử chọn lại giúp mình.';
        }

        return $messages;
    }

    protected function questionDefinitions()
    {
        return [
            'primary_use' => [
                'step' => '01',
                'title' => 'Chọn scene mà bạn muốn build trước.',
                'description' => 'Đây là bối cảnh chi phối mạnh nhất đến form dáng, tính năng và cách shop chấm điểm.',
                'options' => [
                    'office' => [
                        'label' => 'Cafe tập trung',
                        'hint' => 'Ngồi cafe hoặc góc làm việc yên vừa đủ, ưu tiên chống ồn nhẹ và giữ nhịp tập trung.',
                    ],
                    'running' => [
                        'label' => 'Commute',
                        'hint' => 'Di chuyển liên tục trên xe buýt, tàu điện hoặc ngoài đường, cần gọn nhẹ và vào nhạc nhanh.',
                    ],
                    'gaming' => [
                        'label' => 'Gaming đêm',
                        'hint' => 'Setup bàn đêm, cần nghe rõ, mic ổn, vào trận nhanh và giữ cảm giác đeo chắc tay.',
                    ],
                    'study' => [
                        'label' => 'Học 4 tiếng',
                        'hint' => 'Đeo lâu dễ chịu, giữ nhịp học hoặc deep work liên tục mà không mỏi tai.',
                    ],
                ],
            ],
            'form_factor' => [
                'step' => '02',
                'title' => 'Chọn shell cho setup của bạn.',
                'description' => 'Nếu chưa chắc tay, bạn cứ để flexible để hệ thống tự cân bằng theo bối cảnh.',
                'options' => [
                    'over_ear' => [
                        'label' => 'Chụp tai / over-ear',
                        'hint' => 'Ôm tai, cảm giác tập trung, hợp đeo lâu và cho các setup tại chỗ.',
                    ],
                    'true_wireless' => [
                        'label' => 'True wireless',
                        'hint' => 'Gọn, linh hoạt, dễ bỏ túi và hợp cho lịch di chuyển liên tục.',
                    ],
                    'flexible' => [
                        'label' => 'Flexible, miễn là hợp',
                        'hint' => 'Không khóa cứng form factor, ưu tiên để shop tìm mẫu sát nhu cầu nhất.',
                    ],
                ],
            ],
            'priority' => [
                'step' => '03',
                'title' => 'Setup này cần thắng ở tiêu chí nào?',
                'description' => 'Đây là tie breaker để phân biệt các mẫu có điểm gần nhau.',
                'options' => [
                    'noise' => [
                        'label' => 'Tập trung / chống ồn',
                        'hint' => 'Ưu tiên tách âm, giảm xao động và giữ sự liền mạch khi làm việc hay học.',
                    ],
                    'mic' => [
                        'label' => 'Voice chat / mic rõ',
                        'hint' => 'Quan trọng cho họp online, chat voice, gọi điện và teamwork.',
                    ],
                    'comfort' => [
                        'label' => 'Đeo lâu vẫn êm',
                        'hint' => 'Nghiêng về độ êm, độ nhẹ và sự dễ chịu qua nhiều giờ liên tục.',
                    ],
                    'value' => [
                        'label' => 'Giá / hiệu năng',
                        'hint' => 'Chọn mẫu dễ mua, dễ chốt, cân bằng tốt so với mức chi.',
                    ],
                ],
            ],
            'budget' => [
                'step' => '04',
                'title' => 'Khóa tầm ngân sách để chốt setup.',
                'description' => 'Bạn chọn tầm giá thật để shop lọc ra những mẫu có khả năng mua cao nhất.',
                'options' => [
                    'under_1000' => [
                        'label' => 'Dưới 1 triệu',
                        'hint' => 'Dễ chốt nhanh, ưu tiên ngon trong tầm giá.',
                    ],
                    'between_1000_2000' => [
                        'label' => '1 - 2 triệu',
                        'hint' => 'Tầm giá cân bằng, nhiều mẫu vừa dễ mua vừa dễ so sánh.',
                    ],
                    'between_2000_3000' => [
                        'label' => '2 - 3 triệu',
                        'hint' => 'Bắt đầu có thêm tính năng và trải nghiệm ổn hơn.',
                    ],
                    'over_3000' => [
                        'label' => 'Trên 3 triệu',
                        'hint' => 'Ưu tiên mức hoàn thiện, tính năng và độ đã dùng lâu dài.',
                    ],
                ],
            ],
        ];
    }

    protected function battleRounds()
    {
        return [
            'round_1' => [
                'step' => '01',
                'title' => 'Round 1: Scene mở màn',
                'description' => 'Phe nào nghe đúng gu của bạn hơn ngay khi vào cuộc?',
                'options' => [
                    'focus_boost' => [
                        'badge' => 'Làm việc / cafe',
                        'label' => 'Tăng tập trung',
                        'hint' => 'Họp online rõ giọng, làm việc tập trung, giảm bớt xao động xung quanh.',
                        'weights' => [
                            'primary_use' => ['office' => 3, 'study' => 1],
                            'priority' => ['noise' => 1],
                            'form_factor' => ['over_ear' => 1],
                        ],
                    ],
                    'frag_mode' => [
                        'badge' => 'Gaming',
                        'label' => 'Vào trận',
                        'hint' => 'Callout rõ, nghe bước chân tốt, vào trận và chat voice chơi thật tay.',
                        'weights' => [
                            'primary_use' => ['gaming' => 4],
                            'priority' => ['mic' => 1],
                            'form_factor' => ['over_ear' => 1],
                        ],
                    ],
                ],
            ],
            'round_2' => [
                'step' => '02',
                'title' => 'Round 2: Nhịp đeo',
                'description' => 'Bạn nghiêng về dáng đeo nào trong thời gian sử dụng thật?',
                'options' => [
                    'street_move' => [
                        'badge' => 'Gym / di chuyển',
                        'label' => 'Nhịp di chuyển',
                        'hint' => 'Ra đường, gym, cafe, bỏ túi gọn, bật vào là dùng ngay.',
                        'weights' => [
                            'primary_use' => ['running' => 2],
                            'form_factor' => ['true_wireless' => 3],
                            'priority' => ['comfort' => 1],
                            'budget' => ['between_1000_2000' => 1],
                        ],
                    ],
                    'marathon_wear' => [
                        'badge' => 'Đeo lâu',
                        'label' => 'Đeo bền phiên dài',
                        'hint' => 'Đeo 4 tiếng liên tục vẫn êm, ổn định và dễ đắm mình vào việc.',
                        'weights' => [
                            'primary_use' => ['study' => 2, 'office' => 1],
                            'form_factor' => ['over_ear' => 2],
                            'priority' => ['comfort' => 2],
                        ],
                    ],
                ],
            ],
            'round_3' => [
                'step' => '03',
                'title' => 'Round 3: Điều giúp bạn xuống tiền',
                'description' => 'Tính năng nào mới là điểm chốt ở phút cuối?',
                'options' => [
                    'quiet_zone' => [
                        'badge' => 'Chặn ồn',
                        'label' => 'Vùng yên tĩnh',
                        'hint' => 'Khóa bớt tạp âm để tập trung sâu hơn, đặc biệt ở văn phòng và quán cafe.',
                        'weights' => [
                            'priority' => ['noise' => 3],
                            'primary_use' => ['office' => 1],
                            'budget' => ['between_2000_3000' => 1],
                        ],
                    ],
                    'best_bang' => [
                        'badge' => 'Value',
                        'label' => 'Giá ngon dễ chốt',
                        'hint' => 'Mẫu cân bằng, dễ chốt, nhìn giá thấy hợp lý và không bị quá tay.',
                        'weights' => [
                            'priority' => ['value' => 3],
                            'budget' => ['under_1000' => 1, 'between_1000_2000' => 1],
                            'form_factor' => ['flexible' => 1],
                        ],
                    ],
                ],
            ],
            'round_4' => [
                'step' => '04',
                'title' => 'Round 4: Nước mua cuối',
                'description' => 'Nếu phải chốt ngay hôm nay, bạn nghiêng đến kiểu ngân sách nào?',
                'options' => [
                    'premium_push' => [
                        'badge' => 'Premium',
                        'label' => 'Nâng hạng trải nghiệm',
                        'hint' => 'Đầu tư để dùng lâu, ưu tiên độ hoàn thiện và trải nghiệm đủ đã hơn.',
                        'weights' => [
                            'budget' => ['over_3000' => 3, 'between_2000_3000' => 1],
                            'form_factor' => ['over_ear' => 1],
                        ],
                    ],
                    'easy_checkout' => [
                        'badge' => 'Dễ xuống tiền',
                        'label' => 'Chốt đơn gọn',
                        'hint' => 'Giữ ngân sách vừa đẹp để dễ xuống tiền và chốt đơn gọn gàng hơn.',
                        'weights' => [
                            'budget' => ['between_1000_2000' => 2, 'under_1000' => 1],
                            'priority' => ['value' => 1],
                            'form_factor' => ['true_wireless' => 1],
                        ],
                    ],
                ],
            ],
        ];
    }

    protected function deriveAnswersFromBattleChoices(array $battleSelections)
    {
        $questions = $this->questionDefinitions();
        $defaults = [
            'primary_use' => 'office',
            'form_factor' => 'over_ear',
            'priority' => 'comfort',
            'budget' => 'between_1000_2000',
        ];

        $scoreboard = [];

        foreach ($questions as $field => $question) {
            $scoreboard[$field] = array_fill_keys(array_keys($question['options']), 0);
        }

        foreach ($this->battleRounds() as $roundKey => $round) {
            $selectedOption = $battleSelections[$roundKey] ?? null;

            if (!$selectedOption || !isset($round['options'][$selectedOption]['weights'])) {
                continue;
            }

            foreach ($round['options'][$selectedOption]['weights'] as $field => $weightedOptions) {
                foreach ($weightedOptions as $value => $weight) {
                    if (isset($scoreboard[$field][$value])) {
                        $scoreboard[$field][$value] += $weight;
                    }
                }
            }
        }

        $derivedAnswers = [];

        foreach ($scoreboard as $field => $fieldScores) {
            $defaultValue = $defaults[$field];
            $maxScore = max($fieldScores);
            $chosenValue = $defaultValue;

            if ($maxScore > 0) {
                foreach (array_keys($questions[$field]['options']) as $value) {
                    if ($fieldScores[$value] === $maxScore) {
                        $chosenValue = $value;
                        break;
                    }
                }
            }

            $derivedAnswers[$field] = $chosenValue;
        }

        return $derivedAnswers;
    }

    protected function buildBattleRecap(array $battleSelections)
    {
        $recap = [];

        foreach ($this->battleRounds() as $roundKey => $round) {
            $selectedOption = $battleSelections[$roundKey] ?? null;

            if ($selectedOption && isset($round['options'][$selectedOption])) {
                $recap[] = $round['options'][$selectedOption]['label'];
            }
        }

        return $recap;
    }

    protected function buildRecommendation(array $answers, $experienceMode = 'build', array $battleSelections = [])
    {
        $questions = $this->questionDefinitions();
        $selectedLabels = [];

        foreach ($questions as $field => $config) {
            if (isset($answers[$field], $config['options'][$answers[$field]]['label'])) {
                $selectedLabels[] = $config['options'][$answers[$field]]['label'];
            }
        }

        $products = Product::with(['brand', 'cat_info'])
            ->where('status', 'active')
            ->get();

        $accessoryCategory = Category::where('slug', 'phu-kien-am-thanh')->first();
        if ($accessoryCategory) {
            $products = $products->reject(function ($product) use ($accessoryCategory) {
                return (int) $product->cat_id === (int) $accessoryCategory->id;
            })->values();
        }

        $rankedProducts = $products->map(function ($product) use ($answers) {
            return $this->scoreProduct($product, $answers);
        })->sortByDesc('score')->values();

        $topProducts = $rankedProducts->take(3)->values();

        if ($topProducts->count() < 3) {
            $existingProductIds = $topProducts->pluck('product.id')->filter()->all();
            $fallbackProducts = $products->reject(function ($product) use ($existingProductIds) {
                return in_array($product->id, $existingProductIds);
            })->take(3 - $topProducts->count())->map(function ($product) {
                return [
                    'product' => $product,
                    'score' => 0,
                    'fit_label' => 'Thêm để đối chiếu',
                    'reasons' => ['Vẫn là một lựa chọn phù hợp để tham khảo thêm.'],
                    'final_price' => $this->calculateFinalPrice($product),
                ];
            });

            $topProducts = $topProducts->concat($fallbackProducts)->values();
        }

        $fitLabels = ['Khớp nhất', 'Cân bằng tốt', 'Nên xem thêm'];
        $topProducts = $topProducts->values()->map(function ($item, $index) use ($fitLabels) {
            $item['product'] = $this->attachListingMediaToProduct($item['product']);
            $item['fit_label'] = $fitLabels[$index] ?? 'Phù hợp';

            return $item;
        });

        return [
            'experience_mode' => $experienceMode,
            'experience_label' => $this->experienceModes()[$experienceMode]['label'] ?? 'Quiz 45s',
            'experience_recap' => $experienceMode === 'battle' ? $this->buildBattleRecap($battleSelections) : [],
            'profile_title' => $this->buildProfileTitle($answers, $experienceMode),
            'profile_copy' => $this->buildProfileCopy($answers, $selectedLabels, $experienceMode),
            'selected_labels' => $selectedLabels,
            'products' => $topProducts->all(),
            'post' => $this->recommendPost($answers),
        ];
    }

    protected function scoreProduct(Product $product, array $answers)
    {
        $score = 0;
        $reasons = [];
        $finalPrice = $this->calculateFinalPrice($product);
        $categorySlug = optional($product->cat_info)->slug;
        $text = $this->normalizeText(implode(' ', array_filter([
            $product->title,
            strip_tags(html_entity_decode((string) $product->summary)),
            strip_tags(html_entity_decode((string) $product->description)),
            optional($product->cat_info)->title,
            optional($product->brand)->title,
        ])));

        if ((int) $product->stock <= 0) {
            return [
                'product' => $product,
                'score' => -999,
                'reasons' => ['Mẫu này tạm thời hết hàng.'],
                'final_price' => $finalPrice,
            ];
        }

        $score += 2;

        switch ($answers['primary_use']) {
            case 'office':
                if ($this->containsAny($text, ['lam viec', 'van phong', 'tap trung', 'hop online', 'goi dien'])) {
                    $score += 8;
                    $reasons['use'] = 'Hợp nhu cầu làm việc, họp online và tập trung lâu.';
                }

                if ($this->containsAny($text, ['chong on', 'xuyen am'])) {
                    $score += 3;
                    $reasons['mode'] = 'Có tính năng hỗ trợ giảm tạp âm hoặc nghe môi trường linh hoạt.';
                }
                break;

            case 'running':
                if ($this->containsAny($text, ['true wireless', 'thoang tai', 'gon nhe', 'di chuyen', 'xuyen am', 'ket noi nhanh'])) {
                    $score += 8;
                    $reasons['use'] = 'Dáng dễ mang theo, hợp vận động và di chuyển thường xuyên.';
                }

                if ($this->containsAny($text, ['thoang tai', 'xuyen am'])) {
                    $score += 2;
                    $reasons['fit'] = 'Dễ đeo lâu và an toàn hơn khi cần nghe xung quanh.';
                }
                break;

            case 'gaming':
                if ($this->containsAny($text, ['gaming', 'game', 'buoc chan', 'headset'])) {
                    $score += 8;
                    $reasons['use'] = 'Khớp với nhu cầu gaming và giải trí tại nhà.';
                }

                if ($this->containsAny($text, ['mic', 'micro', 'goi dien', 'hop online'])) {
                    $score += 2;
                    $reasons['voice'] = 'Mic và khả năng bắt giọng là điểm cộng để giao tiếp trong trận.';
                }
                break;

            case 'study':
                if ($this->containsAny($text, ['hoc online', 'hoc tap', 'sinh vien', 'goi dien', 'mic'])) {
                    $score += 8;
                    $reasons['use'] = 'Rất hợp cho học online và dùng nhiều mỗi ngày.';
                }

                if ($this->containsAny($text, ['deo lau', 'thoang tai', 'dem tai em', 'nhe'])) {
                    $score += 2;
                    $reasons['fit'] = 'Đeo lâu dễ chịu hơn khi học và làm việc liên tục.';
                }
                break;
        }

        if ($answers['form_factor'] === 'over_ear') {
            if ($categorySlug === 'tai-nghe-chup-tai' || $this->containsAny($text, ['chup tai', 'over ear'])) {
                $score += 5;
                $reasons['form'] = 'Đúng kiểu chụp tai bạn đang nghiêng tới.';
            } else {
                $score -= 1;
            }
        }

        if ($answers['form_factor'] === 'true_wireless') {
            if ($categorySlug === 'tai-nghe-true-wireless' || $this->containsAny($text, ['true wireless', 'bluetooth'])) {
                $score += 5;
                $reasons['form'] = 'Đúng dáng true wireless gọn nhẹ bạn vừa chọn.';
            } else {
                $score -= 1;
            }
        }

        if ($answers['priority'] === 'noise' && $this->containsAny($text, ['chong on', 'xuyen am', 'tap trung'])) {
            $score += 6;
            $reasons['priority'] = 'Ưu tiên tốt cho việc tập trung và giảm tạp âm.';
        }

        if ($answers['priority'] === 'mic' && $this->containsAny($text, ['mic', 'micro', 'goi dien', 'hop online'])) {
            $score += 6;
            $reasons['priority'] = 'Mic và khả năng thoại là điểm mạnh của mẫu này.';
        }

        if ($answers['priority'] === 'comfort' && $this->containsAny($text, ['deo lau', 'dem tai em', 'thoang tai', 'nhe', 'om tai'])) {
            $score += 6;
            $reasons['priority'] = 'Thiên về độ êm, độ nhẹ và sự dễ chịu khi đeo lâu.';
        }

        if ($answers['priority'] === 'value') {
            if ($finalPrice <= 1500000 || (float) $product->discount > 0) {
                $score += 6;
                $reasons['priority'] = 'Giá dễ tiếp cận, khá cân bằng so với nhu cầu đã chọn.';
            } elseif ($finalPrice <= 2200000) {
                $score += 3;
            }
        }

        $budgetScore = $this->budgetScore($finalPrice, $answers['budget']);
        $score += $budgetScore['score'];
        if ($budgetScore['reason']) {
            $reasons['budget'] = $budgetScore['reason'];
        }

        if ((float) $product->discount > 0) {
            $score += 1;
            $reasons['deal'] = 'Đang có mức giảm giá để chốt đơn nhẹ hơn.';
        }

        if ($product->condition === 'new') {
            $score += 1;
        }

        if ($product->condition === 'hot' && in_array($answers['primary_use'], ['gaming', 'study'], true)) {
            $score += 1;
        }

        $visibleReasons = array_slice(array_values(array_unique($reasons)), 0, 3);
        if (empty($visibleReasons)) {
            $visibleReasons[] = 'Có cấu hình và tầm giá khá sát với bộ câu trả lời của bạn.';
        }

        return [
            'product' => $product,
            'score' => $score,
            'reasons' => $visibleReasons,
            'final_price' => $finalPrice,
        ];
    }

    protected function budgetScore($finalPrice, $budget)
    {
        switch ($budget) {
            case 'under_1000':
                if ($finalPrice <= 1000000) {
                    return ['score' => 6, 'reason' => 'Nằm gọn trong tầm ngân sách dưới 1 triệu.'];
                }

                if ($finalPrice <= 1300000) {
                    return ['score' => 3, 'reason' => 'Hơi vượt một chút, nhưng vẫn là mức dễ cân nhắc.'];
                }

                return ['score' => -3, 'reason' => null];

            case 'between_1000_2000':
                if ($finalPrice >= 1000000 && $finalPrice <= 2000000) {
                    return ['score' => 6, 'reason' => 'Khớp đẹp với tầm 1 - 2 triệu bạn đã chọn.'];
                }

                if ($finalPrice >= 800000 && $finalPrice <= 2300000) {
                    return ['score' => 3, 'reason' => 'Giá nằm sát vùng ngân sách để bạn cân nhắc thêm.'];
                }

                return ['score' => -2, 'reason' => null];

            case 'between_2000_3000':
                if ($finalPrice >= 2000000 && $finalPrice <= 3000000) {
                    return ['score' => 6, 'reason' => 'Phù hợp tầm 2 - 3 triệu và dễ chốt thực tế.'];
                }

                if ($finalPrice >= 1700000 && $finalPrice <= 3300000) {
                    return ['score' => 3, 'reason' => 'Nằm rất gần tầm giá bạn đang cần.'];
                }

                return ['score' => -2, 'reason' => null];

            case 'over_3000':
                if ($finalPrice >= 3000000) {
                    return ['score' => 6, 'reason' => 'Đúng nhóm trên 3 triệu mà bạn sẵn sàng đầu tư.'];
                }

                if ($finalPrice >= 2500000) {
                    return ['score' => 3, 'reason' => 'Thấp hơn mức dự kiến một chút nhưng vẫn rất đáng xem.'];
                }

                return ['score' => -2, 'reason' => null];
        }

        return ['score' => 0, 'reason' => null];
    }

    protected function recommendPost(array $answers)
    {
        $posts = Post::where('status', 'active')->orderBy('id', 'DESC')->get();

        if ($posts->isEmpty()) {
            return null;
        }

        $rankedPosts = $posts->map(function ($post) use ($answers) {
            $text = $this->normalizeText(implode(' ', array_filter([
                $post->title,
                strip_tags(html_entity_decode((string) $post->summary)),
                strip_tags(html_entity_decode((string) $post->description)),
                $post->tags,
            ])));

            $score = 0;
            $reason = 'Bài viết này sẽ giúp bạn đọc thêm trước khi chốt mẫu tai nghe.';

            switch ($answers['primary_use']) {
                case 'office':
                    if ($this->containsAny($text, ['van phong', 'chong on', 'xuyen am', 'tap trung'])) {
                        $score += 6;
                        $reason = 'Bài viết này đi thẳng vào bài toán tập trung và môi trường văn phòng.';
                    }
                    break;

                case 'running':
                    if ($this->containsAny($text, ['chay bo', 'true wireless', 'van dong', 'xuyen am'])) {
                        $score += 6;
                        $reason = 'Bài viết này rất sát với nhu cầu chạy bộ và di chuyển.';
                    }
                    break;

                case 'gaming':
                    if ($this->containsAny($text, ['bao quan', 'ben pin', 'dem tai'])) {
                        $score += 4;
                        $reason = 'Shop chưa có bài gaming riêng, nên đây là bài hữu ích để dùng headset bền hơn.';
                    }
                    break;

                case 'study':
                    if ($this->containsAny($text, ['van phong', 'tap trung', 'bao quan', 'pin'])) {
                        $score += 5;
                        $reason = 'Bài viết này hợp cho nhu cầu học online và dùng tai nghe mỗi ngày.';
                    }
                    break;
            }

            if ($answers['priority'] === 'noise' && $this->containsAny($text, ['chong on', 'xuyen am'])) {
                $score += 3;
            }

            if ($answers['priority'] === 'comfort' && $this->containsAny($text, ['deo', 'om tai', 'van dong', 'dem tai'])) {
                $score += 2;
            }

            if ($answers['priority'] === 'value' && $this->containsAny($text, ['bao quan', 'ben pin'])) {
                $score += 1;
            }

            if ($answers['form_factor'] === 'true_wireless' && $this->containsAny($text, ['true wireless'])) {
                $score += 2;
            }

            return [
                'post' => $post,
                'score' => $score,
                'reason' => $reason,
            ];
        })->sortByDesc('score')->values();

        return $rankedPosts->first();
    }

    protected function buildProfileTitle(array $answers, $experienceMode = 'build')
    {
        if ($experienceMode === 'arcade') {
            $titles = [
                'office' => 'Bạn đang nghiêng về một setup tập trung, gọn gàng và hợp làm việc sâu.',
                'running' => 'Bạn đang hợp với một mẫu gọn nhẹ, linh hoạt và dễ mang theo liên tục.',
                'gaming' => 'Bạn đang thiên về một headset nghe rõ, chat ổn và vào game rất nhanh.',
                'study' => 'Bạn đang khớp với một mẫu đeo lâu dễ chịu và dùng đều mỗi ngày.',
            ];

            return $titles[$answers['primary_use']] ?? 'Bạn đang mở ra một hồ sơ tai nghe khá sát với cách chơi của mình.';
        }

        if ($experienceMode === 'battle') {
            $titles = [
                'office' => 'Sau 4 round, bạn nghiêng rõ về một setup tập trung và giao tiếp gọn gàng.',
                'running' => 'Sau 4 round, gu của bạn rất hợp một mẫu gọn nhẹ, linh hoạt và dễ mang theo.',
                'gaming' => 'Sau 4 round, bạn rõ ràng nghiêng về headset nghe rõ, chat ổn và vào trận nhanh.',
                'study' => 'Sau 4 round, bạn hợp nhất với một setup đeo lâu dễ chịu và dùng đều mỗi ngày.',
            ];

            return $titles[$answers['primary_use']] ?? 'Sau 4 round, bạn đang nghiêng về một mẫu tai nghe sát bối cảnh dùng thật.';
        }

        $titles = [
            'office' => 'Setup của bạn ưu tiên sự tập trung và giao tiếp rõ ràng.',
            'running' => 'Setup của bạn nghiêng về sự gọn nhẹ, ổn định và dễ mang theo.',
            'gaming' => 'Setup của bạn cần một headset nghe rõ, chat thoại ổn và vào việc nhanh.',
            'study' => 'Setup của bạn hợp với học online và dùng đều mỗi ngày.',
        ];

        return $titles[$answers['primary_use']] ?? 'Bạn đang tìm một mẫu tai nghe hợp đúng cảnh sử dụng.';
    }

    protected function buildProfileCopy(array $answers, array $selectedLabels, $experienceMode = 'build')
    {
        $segments = [];

        if (!empty($selectedLabels)) {
            if ($experienceMode === 'arcade') {
                $segments[] = 'Từ cách bạn chơi, hệ thống đang đọc ra hồ sơ: ' . implode(' | ', $selectedLabels) . '.';
            } elseif ($experienceMode === 'battle') {
                $segments[] = '4 round vừa rồi đang đẩy bạn về hồ sơ: ' . implode(' | ', $selectedLabels) . '.';
            } else {
                $segments[] = 'Scene bạn vừa dựng đang nghiêng về: ' . implode(' | ', $selectedLabels) . '.';
            }
        }

        if ($answers['form_factor'] === 'flexible') {
            $segments[] = 'Vì bạn mở về dáng đeo, hệ thống ưu tiên bối cảnh sử dụng và tầm giá trước.';
        } else {
            $segments[] = 'Vì bạn đã khóa form factor, bảng điểm sẽ nghiêng mạnh hơn về dáng tai nghe này.';
        }

        return implode(' ', $segments);
    }

    protected function calculateFinalPrice(Product $product)
    {
        return $product->price - (($product->price * $product->discount) / 100);
    }

    protected function normalizeText($text)
    {
        $normalizedText = Str::lower(Str::ascii((string) $text));
        $normalizedText = preg_replace('/[^a-z0-9\s]/', ' ', $normalizedText);

        return trim(preg_replace('/\s+/', ' ', $normalizedText));
    }

    protected function containsAny($text, array $keywords)
    {
        foreach ($keywords as $keyword) {
            if (strpos($text, $keyword) !== false) {
                return true;
            }
        }

        return false;
    }
}

<?php

namespace Database\Factories;

use App\Models\Review;
use App\Models\User;
use App\Models\Shop;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReviewFactory extends Factory
{
    protected $model = Review::class;

    private function getReviewComment($genreId)
    {
        $comments = [
            1 => [ // G1: イタリアン
                'パスタの茹で加減が絶妙で、ソースとの相性も抜群でした。',
                '本格的なイタリアンの味わいで、特に前菜のアンティパストが印象的でした。',
                'ピザの生地が薄くて香ばしく、トッピングのバランスも良かったです。',
                'ワインのセレクションが充実していて、料理とのペアリングも素晴らしかったです。',
                'シェフのこだわりが感じられる一品一品が魅力的でした。'
            ],
            2 => [ // G2: ラーメン
                'スープの味が深く、チャーシューも柔らかくて美味しかったです。',
                '麺のコシが強く、スープとの相性が抜群でした。',
                'トッピングの量が多く、コスパが良いと感じました。',
                'スープの温度が丁度良く、最後まで美味しく食べられました。',
                '店舗の雰囲気も清潔で、食べやすい環境でした。'
            ],
            3 => [ // G3: 居酒屋
                'おつまみの種類が豊富で、お酒との相性が良かったです。',
                '雰囲気が明るく、居心地の良い空間でした。',
                'スタッフの接客が丁寧で、リピートしたくなりました。',
                '料理の量が適切で、お酒を飲みながら楽しめました。',
                '深夜まで営業しているので、夜食にも最適です。'
            ],
            4 => [ // G4: 日本料理
                '旬の食材を使った料理が美味しく、季節感を感じられました。',
                '盛り付けが美しく、目でも楽しめる料理でした。',
                '出汁の味が深く、素材の味を活かした料理でした。',
                '和食の作法に従った配膳で、本格的な雰囲気でした。',
                'お茶の種類も豊富で、料理との相性が良かったです。'
            ],
            5 => [ // G5: 焼肉
                '肉の品質が高く、特に和牛の味わいが素晴らしかったです。',
                '焼き具合のアドバイスが丁寧で、美味しく食べられました。',
                'タレの種類が豊富で、肉との相性が良かったです。',
                '換気が良く、焼肉の匂いが気になりませんでした。',
                '野菜の種類も多く、バランスの取れた食事ができました。'
            ]
        ];

        return $comments[$genreId][array_rand($comments[$genreId])];
    }

    public function definition()
    {
        $genreId = $this->faker->numberBetween(1, 5);

        return [
            'user_id' => User::where('role', 3)->inRandomOrder()->first()->id,
            'shop_id' => Shop::inRandomOrder()->first()->id,
            'rating' => $this->faker->numberBetween(1, 5),
            'comment' => $this->getReviewComment($genreId),
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'updated_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
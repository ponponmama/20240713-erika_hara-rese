<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>shop_list</title>
    <link rel="stylesheet" href="{{ asset('css/shop_list.css') }}">
</head>

    <body>
        <div class="shop_table">
            <table class="shop_list">
                <tr class="header-row">
                    <th>店舗名</th>
                    <th>地域</th>
                    <th>ジャンル</th>
                    <th>店舗概要</th>
                    <th>画像ＵＲＬ</th>
                </tr>
                <tbody>
                    <tr>
                        <td>仙人</td>
                        <td>東京都</td>
                        <td>寿司</td>
                        <td>料理長厳選の食材から作る寿司を用いたコースをぜひお楽しみください。食材・味・価格、お客様の満足度を徹底的に追及したお店です。特別な日のお食事、ビジネス接待まで気軽に使用することができます。</td>
                        <td><img src="{{ asset('images/sushi.jpg') }}" alt="寿司職人の写真"></td>
                        <td><a href="{{ asset('images/sushi.jpg') }}" target="_blank">画像URL</a></td>
                        <td><a href="{{ route('shop.detail', $shop->id) }}">{{ $shop->shop_name }}</a></td>
                    </tr>
                    <tr>
                        <td>牛助</td>
                        <td>大阪府</td>
                        <td>焼肉</td>
                        <td>焼肉業界で20年間経験を積み、肉を熟知したマスターによる実力派焼肉店。長年の実績とお付き合いをもとに、なかなか食べられない希少部位も仕入れております。また、ゆったりとくつろげる空間はお仕事終わりの一杯や女子会にぴったりです。</td>
                        <td><img src="{{ asset('images/yakiniku.jpg') }}" alt="肉の盛り合わせ写真"></td>
                    </tr>
                    <tr>
                        <td>戦慄</td>
                        <td>福岡県</td>
                        <td>居酒屋</td>
                        <td>気軽に立ち寄れる昔懐かしの大衆居酒屋です。キンキンに冷えたビールを、なんと199円で。鳥かわ煮込み串は販売総数100000本突破の名物料理です。仕事帰りに是非御来店ください。</td>
                        <td><img src="{{ asset('images/izakaya.jpg') }}" alt="テーブル席一覧の写真"></td>
                    </tr>
                    <tr>
                        <td>ルーク</td>
                        <td>東京都</td>
                        <td>イタリアン</td>
                        <td>都心にひっそりとたたずむ、古民家を改築した落ち着いた空間です。イタリアで修業を重ねたシェフによるモダンなイタリア料理とソムリエセレクトによる厳選ワインとのペアリングが好評です。ゆっくりと上質な時間をお楽しみください。</td>
                        <td><img src="{{ asset('images/italian.jpg') }}" alt="ワインとピッツァの写真"></td>
                    </tr>
                    <tr>
                        <td>志摩屋</td>
                        <td>福岡県</td>
                        <td>ラーメン</td>
                        <td>ラーメン屋とは思えない店内にはカウンター席はもちろん、個室も用意してあります。ラーメンはこってり系・あっさり系ともに揃っています。その他豊富な一品料理やアルコールも用意しており、居酒屋としても利用できます。ぜひご来店をお待ちしております。</td>
                        <td><img src="{{ asset('images/ramen.jpg') }}" alt="カウンター越しにラーメンを作成している写真"></td>
                    </tr>
                    <tr>
                        <td>香</td>
                        <td>東京都</td>
                        <td>焼肉</td>
                        <td>大小さまざまなお部屋をご用意してます。デートや接待、記念日や誕生日など特別な日にご利用ください。皆様のご来店をお待ちしております。</td>
                        <td><img src="{{ asset('images/yakiniku.jpg') }}" alt="肉の盛り合わせ写真"></td>
                    </tr>
                    <tr>
                        <td>JJ</td>
                        <td>大阪府</td>
                        <td>イタリアン</td>
                        <td>イタリア製ピザ窯芳ばしく焼き上げた極薄のミラノピッツァや厳選されたワインをお楽しみいただけます。女子会や男子会、記念日やお誕生日会にもオススメです。</td>
                        <td><img src="{{ asset('images/italian.jpg') }}" alt="ワインとピッツァの写真"></td>
                    </tr>
                    <tr>
                        <td>らーめん極み</td>
                        <td>東京都</td>
                        <td>ラーメン</td>
                        <td>一杯、一杯心を込めて職人が作っております。味付けは少し濃いめです。 食べやすく最後の一滴まで美味しく飲めると好評です。</td>
                        <td><img src="{{ asset('images/ramen.jpg') }}" alt="カウンター越しにラーメンを作成している写真"></td>
                    </tr>
                    <tr>
                        <td>鳥雨</td>
                        <td>大阪府</td>
                        <td>居酒屋</td>
                        <td>素材の旨味を存分に引き出す為に、塩焼を中心としたお店です。比内地鶏を中心に、厳選素材を職人が備長炭で豪快に焼き上げます。清潔な内装に包まれた大人の隠れ家で贅沢で優雅な時間をお過ごし下さい。</td>
                        <td><img src="{{ asset('images/izakaya.jpg') }}" alt="テーブル席一覧の写真"></td>
                    </tr>
                    <tr>
                        <td>築地色合</td>
                        <td>東京都</td>
                        <td>寿司</td>
                        <td>鮨好きの方の為の鮨屋として、迫力ある大きさの握りを1貫ずつ提供致します。</td>
                        <td><img src="{{ asset('images/sushi.jpg') }}" alt="寿司職人の写真"></td>
                    </tr>
                    <tr>
                        <td>晴海</td>
                        <td>大阪府</td>
                        <td>焼肉</td>
                        <td>毎年チャンピオン牛を買い付け、仙台市長から表彰されるほどの上質な仕入れをする精肉店オーナーの本当に美味しい国産牛を食べてもらいたいという思いから誕生したお店です。</td>
                        <td><img src="{{ asset('images/yakiniku.jpg') }}" alt="肉の盛り合わせ写真"></td>
                    </tr>
                    <tr>
                        <td>三子</td>
                        <td>福岡県</td>
                        <td>焼肉</td>
                        <td>最高級の美味しいお肉で日々の疲れを軽減していただければと贅沢にサーロインを盛り込んだ御膳をご用意しております。</td>
                        <td><img src="{{ asset('images/yakiniku.jpg') }}" alt="肉の盛り合わせ写真"></td>
                    </tr>
                    <tr>
                        <td>八戒</td>
                        <td>東京都</td>
                        <td>居酒屋</td>
                        <td>当店自慢の鍋や焼き鳥などお好きなだけ堪能できる食べ放題プランをご用意しております。飲み放題は2時間と3時間がございます。</td>
                        <td><img src="{{ asset('images/izakaya.jpg') }}" alt="テーブル席一覧の写真"></td>
                    </tr>
                    <tr>
                        <td>福助</td>
                        <td>大阪府</td>
                        <td>寿司</td>
                        <td>ミシュラン掲載店で磨いた、寿司職人の旨さへのこだわりはもちろん、 食事をゆっくりと楽しんでいただける空間作りも意識し続けております。 接待や大切なお食事にはぜひご利用ください。</td>
                        <td><img src="{{ asset('images/sushi.jpg') }}" alt="寿司職人の写真"></td>
                    </tr>
                    <tr>
                        <td>ラー北</td>
                        <td>東京都</td>
                        <td>ラーメン</td>
                        <td>お昼にはランチを求められるサラリーマン、夕方から夜にかけては、学生や会社帰りのサラリーマン、小上がり席もありファミリー層にも大人気です。</td>
                        <td><img src="{{ asset('images/ramen.jpg') }}" alt="カウンター越しにラーメンを作成している写真"></td>
                    </tr>
                    <tr>
                        <td>翔</td>
                        <td>大阪府</td>
                        <td>居酒屋</td>
                        <td>博多出身の店主自ら厳選した新鮮な旬の素材を使ったコース料理をご提供します。一人一人のお客様に目が届くようにしております。</td>
                        <td><img src="{{ asset('images/izakaya.jpg') }}" alt="テーブル席一覧の写真"></td>
                    </tr>
                    <tr>
                        <td>経緯</td>
                        <td>東京都</td>
                        <td>寿司</td>
                        <td>職人が一つ一つ心を込めて丁寧に仕上げた、江戸前鮨ならではの味をお楽しみ頂けます。鮨に合った希少なお酒も数多くご用意しております。他にはない海鮮太巻き、当店自慢の蒸し鮑、是非ご賞味下さい。</td>
                        <td><img src="{{ asset('images/sushi.jpg') }}" alt="寿司職人の写真"></td>
                    </tr>
                    <tr>
                        <td>漆</td>
                        <td>東京都</td>
                        <td>焼肉</td>
                        <td>店内に一歩足を踏み入れると、肉の焼ける音と芳香が猛烈に食欲を掻き立ててくる。そんな漆で味わえるのは至極の焼き肉です。</td>
                        <td><img src="{{ asset('images/yakiniku.jpg') }}" alt="肉の盛り合わせ写真"></td>
                    </tr>
                    <tr>
                        <td>THE TOOL</td>
                        <td>福岡県</td>
                        <td>イタリアン</td>
                        <td>非日常的な空間で日頃の疲れを癒し、ゆったりとした上質な時間を過ごせる大人の為のレストラン&バーです。</td>
                        <td><img src="{{ asset('images/italian.jpg') }}" alt="ワインとピッツァの写真"></td>
                    </tr>
                    <tr>
                        <td>木船</td>
                        <td>大阪府</td>
                        <td>寿司</td>
                        <td>毎日店主自ら市場等に出向き、厳選した魚介類が、お鮨をはじめとした繊細な料理に仕立てられます。また、選りすぐりの種類豊富なドリンクもご用意しております。</td>
                        <td><img src="{{ asset('images/sushi.jpg') }}" alt="寿司職人の写真"></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </body>
</html>
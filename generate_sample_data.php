<?php
echo "generate_sample_data.php: script starting\n"; // DEBUG
require_once __DIR__ . '/includes/database.php';
require_once __DIR__ . '/src/functions.php'; // For create_thread, create_post

// --- Configuration ---
$total_target_threads = 15; // Restored
$total_target_posts = 1000; // Restored
// $enable_data_wipe = true; // This will now be a parameter to populate_database

// --- Story Thread Definitions ---

// Story 1: The Creepy Hospital Wing
$story_hospital = [
    'title' => "廃病院の閉鎖病棟に迷い込んだんだが…", // "I've wandered into the closed ward of an abandoned hospital..."
    'target_posts' => 234,
    'protagonist' => "イッチ", // "OP" (Original Poster)
    'posts' => [
        ['author' => null, 'body' => "マジでヤバいかもしれん…誰かいるか？"], // "This might be really bad... Anyone here?"
        ['author' => null, 'body' => "とりあえず状況説明。近所の廃病院、昔から気になってて今日初めて忍び込んだ。\nそしたら、閉鎖されてるはずの奥の病棟にいつの間にか…"], // "Okay, situation report. Abandoned hospital in my neighborhood, always been curious, snuck in for the first time today. Then, somehow I ended up in the inner ward that's supposed to be closed off..."
        ['author' => '名無しさん', 'body' => "イッチ大丈夫か？kwsk"], // "OP, you okay? Details please"
        ['author' => '名無しさん', 'body' => "うpはよ"], // "Upload pics quick"
        ['author' => null, 'body' => ">>3 ありがとう。スマホの電波ギリギリだ。\n>>4 今暗くて何も映らん。懐中電灯はある。\nなんか古いカルテとか散乱してる。薬品の匂いもする…"], // ">>3 Thanks. Phone signal is barely there. >>4 It's too dark now, can't capture anything. I have a flashlight. There are old medical records scattered around. Smells like chemicals too..."
        ['author' => null, 'body' => "写真撮ってみた。ブレてるけど… [image_url:http://example.com/path/to/hospital_corridor.jpg]"], // "Took a photo. It's blurry but... [image_url]"
        ['author' => '名無しさん', 'body' => "おいおい、マジかよ…"], // "Whoa, seriously...?"
        ['author' => '名無しさん', 'body' => "それっぽい雰囲気出てんなｗ"], // "That looks creepy lol"
        ['author' => null, 'body' => "奥から物音が聞こえる気がするんだが…気のせいか？ (´・ω・｀)"], // "I think I hear a noise from further in... or is it my imagination? (´・ω・｀)"
        // ... more posts to reach ~234
    ]
];

// Story 2: My Neighbor is a Spy?
$story_spy = [
    'title' => "隣の部屋の住人がどうも怪しい…スパイかもしれない", // "My neighbor is really suspicious... Might be a spy"
    'target_posts' => 189,
    'protagonist' => "イッチ",
    'posts' => [
        ['author' => null, 'body' => "ここ数ヶ月、隣の部屋の住人の行動がおかしいんだ。\n毎晩深夜に誰かと小声で話してるし、時々外国語も混じる。\nゴミ出しの曜日も守らないし…"], // "For the past few months, my neighbor's behavior has been strange. Every night, they whisper with someone, sometimes mixed with a foreign language. They don't follow the trash day rules either..."
        ['author' => '名無しさん', 'body' => "ｗｗｗ スパイ認定早すぎだろ"], // "lol, jumping to 'spy' too quickly"
        ['author' => '名無しさん', 'body' => "糖質乙"], // "Schizo much?" (derogatory)
        ['author' => null, 'body' => "いや、マジなんだって！\n昨日なんて、黒服の男たちが隣の部屋に入っていくのを見たんだ。\nなんかヤバい取引してるんじゃ…"], // "No, I'm serious! Yesterday, I saw men in black suits going into their room. Maybe they're making some dangerous deal..."
        ['author' => '名無しさん', 'body' => "映画の観すぎじゃね？(ﾟ∀ﾟ)"], // "Watched too many movies? (ﾟ∀ﾟ)"
        // ... more posts
    ]
];

// Story 3: Found a Lost Retro Game
$story_retro_game = [
    'title' => "押し入れから激レアなファミコンソフト見つけたったｗｗｗ", // "Found a super rare Famicom game in my closet lol"
    'target_posts' => 156,
    'protagonist' => "ゲーム発見者", // "Game Discoverer"
    'posts' => [
        ['author' => null, 'body' => "掃除してたら、ファミコンカセットが箱いっぱい出てきたんだ。\nその中に一本だけ、見たことないタイトルのがあって…ググっても情報少ない。\nこれってもしかしてお宝か？"], // "Was cleaning and found a whole box of Famicom cartridges. Among them, there's one with a title I've never seen... Not much info when I google it. Could this be a treasure?"
        ['author' => '名無しさん', 'body' => "タイトルはよ"], // "Title, quick"
        ['author' => '名無しさん', 'body' => "写真うp"], // "Upload pic"
        ['author' => null, 'body' => "タイトルは「異次元バスターズDX」ってやつ。\n状態はかなり良い。とりあえず起動してみるわ！"], // "The title is 'Ijigen Busters DX'. Condition is pretty good. Gonna try booting it up!"
        ['author' => '名無しさん', 'body' => "wktk"], // "Excited" (waiting with anticipation)
        // ... more posts
    ]
];

$stories = [$story_hospital, $story_spy, $story_retro_game];

// --- Generic Thread Content ---
$generic_thread_titles = [
    "一番好きなラーメンの味は？", // "Favorite ramen flavor?"
    "最近見た面白いアニメ Part.3", // "Interesting anime I saw recently Part.3"
    "PCのスペック晒してけｗｗｗ", // "Share your PC specs lol"
    "おすすめのスマホゲー教えろください", // "Tell me your recommended mobile games please"
    "田舎暮らしのメリット・デメリット", // "Pros and cons of living in the countryside"
    "お前らの夜食テロ画像見せろ", // "Show me your late-night food porn pics"
    "筋トレしてる奴ちょっとこい", // "People doing strength training, come here a bit"
    "最近のJ-POPについてどう思う？", // "What do you think about recent J-POP?"
    "コスパ最強のイヤホンはこれだろ", // "This is the best cost-performance earphone, right?"
    "一人暮らしの寂しさを紛らわす方法", // "Ways to relieve loneliness when living alone"
    "タイムマシンがあったら何する？", // "What would you do if you had a time machine?"
    "SF映画の最高傑作って何？", // "What's the masterpiece of sci-fi movies?"
];

$common_phrases = [
    "それは草", "わかる", "乙です！", "GJ!", "kwsk頼む", "はよ", "m9(＾Д＾)ﾌﾟｷﾞｬｰ", "ｗｗｗ", "ｗｗ", "草ｗ", "大草原", "草不可避",
    "異議なし！", "異論は認める", "（　＾ω＾）・・・", "もうだめぽ", "orz", "キタ━━━━(ﾟ∀ﾟ)━━━━!!",
    "wktk", "詳細希望", "あるあるｗ", "ないわー", "検討します", "様子見", "次スレはよ",
    "保守", "あげ", "sage", ">>1乙", ">>100乙", "乙カレー", "自演乙",
    "今北産業", // "Just arrived, 3-line summary please"
    "長文乙", "チラ裏でやれ", // "Do it on the back of a flyer (i.e. don't care)"
    "おｋ", "ダメ、絶対", "せやな", "せやろか？", "ソースは？", "脳内ソース乙",
    "釣れますか？", // "Are you fishing (for reactions)?"
    "(´・ω・｀)ｼｮﾎﾞｰﾝ", "( ´ー｀)ﾌｩｰ．．．", "（；＾ω＾）おっおっおっ", "ヽ(ﾟ∀ﾟ)ﾉ ﾊﾟｯ☆"
];

$ascii_arts = [
    "　∧＿∧　　／￣￣￣￣￣\n　(　´∀｀)＜　おつかれー\n　(　　　 )　＼＿＿＿＿＿\n　｜ ｜　|\n　(＿_)＿)",
    "　　 　 ∧＿∧\n　　　　( ´･ω･)\n　　　   (　つ┳⊃\n　　　   ε (_)へ⌒ヽﾌ\n　　　 （（ 　（__（_＿）",
    "　　 Λ＿Λ\n　（ ・∀・）\n　（　∪ ∪\n　と＿_)__)",
    "m9(＾Д＾)ﾌﾟｷﾞｬｰ!!",
    "orz",
    "く(´・ω・｀)／",
    "( ´Д｀)ﾉ(´Д｀ )ﾉ(´Д｀ )ﾉ ｵｰﾙスター感謝祭かよ!",
];

$joke_authors = ['名無しさん', '通りすがりの天才', 'ID:AbcDeFgH', 'ID:???', 'VIPPER', '情報通'];

// --- Helper Functions for Content Generation ---

function generate_random_text($phrases, $ascii_arts, $num_paragraphs = 1) {
    $text = "";
    for ($i = 0; $i < $num_paragraphs; $i++) {
        $paragraph = [];
        $num_sentences = rand(1, 4);
        for ($j = 0; $j < $num_sentences; $j++) {
            if (rand(1, 10) <= 2) { // 20% chance of ASCII art
                $paragraph[] = $ascii_arts[array_rand($ascii_arts)];
            } else {
                $paragraph[] = $phrases[array_rand($phrases)];
            }
        }
        $text .= implode(" ", $paragraph) . "\n";
    }
    return trim($text);
}

function get_random_author($joke_authors) {
    // Overwhelmingly '名無しさん'
    if (rand(1, 10) <= 8) return '名無しさん';
    return $joke_authors[array_rand($joke_authors)];
}

// Slightly adjust timestamp (simplified: subtract random seconds from current time)
function get_adjusted_timestamp($base_pdo_datetime, $seconds_to_subtract) {
    try {
        $dt = new DateTime($base_pdo_datetime);
        $dt->sub(new DateInterval("PT{$seconds_to_subtract}S"));
        return $dt->format('Y-m-d H:i:s');
    } catch (Exception $e) {
        return $base_pdo_datetime; // Fallback
    }
}

// Override create_post to allow specific created_at
// This is a bit of a hack; ideally, the original create_post would support this
function create_post_with_timestamp($pdo, $thread_id, $author_name, $body, $image_url = null, $created_at = null) {
    $author_name = sanitize_input($author_name);
    $body = sanitize_input($body);
    $image_url = $image_url ? sanitize_input($image_url) : null;

    if (empty($body) || empty($thread_id)) {
        return false;
    }
    if (empty($author_name)) {
        $author_name = '名無しさん';
    }

    try {
        if ($created_at) {
            $sql = "INSERT INTO posts (thread_id, author_name, body, image_url, created_at) VALUES (:thread_id, :author_name, :body, :image_url, :created_at)";
        } else {
            $sql = "INSERT INTO posts (thread_id, author_name, body, image_url) VALUES (:thread_id, :author_name, :body, :image_url)";
        }
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':thread_id', $thread_id, PDO::PARAM_INT);
        $stmt->bindParam(':author_name', $author_name, PDO::PARAM_STR);
        $stmt->bindParam(':body', $body, PDO::PARAM_STR);
        $stmt->bindParam(':image_url', $image_url, PDO::PARAM_STR);
        if ($created_at) {
            $stmt->bindParam(':created_at', $created_at, PDO::PARAM_STR);
        }
        return $stmt->execute();
    } catch (PDOException $e) {
        error_log("Error creating post with timestamp: " . $e->getMessage());
        return false;
    }
}


// --- Main Data Generation Function ---
function populate_database(
    $pdo,
    $stories,
    $generic_thread_titles,
    $common_phrases,
    $ascii_arts,
    $joke_authors,
    $total_target_threads,
    $total_target_posts,
    $enable_wipe = false // Default to false
) {
    echo "generate_sample_data.php: populate_database() called.\n"; // DEBUG
    echo "populate_database(): enable_wipe is " . ($enable_wipe ? 'true' : 'false') . "\n"; // DEBUG

    if ($enable_wipe) {
        echo "Wiping existing data from posts and threads tables...\n";
        $pdo->exec("DELETE FROM posts");
        $pdo->exec("DELETE FROM threads");
        // Reset autoincrement counters for SQLite
        $pdo->exec("DELETE FROM sqlite_sequence WHERE name='threads'");
        $pdo->exec("DELETE FROM sqlite_sequence WHERE name='posts'");
        echo "Data wiped.\n";
    }

    $total_posts_created = 0;
    $threads_created_count = 0;
    $current_time = new DateTime();


    // Generate Story Threads
    echo "Generating story threads...\n";
    foreach ($stories as $story_index => $story) {
        if ($threads_created_count >= $total_target_threads) break;

        echo "Creating story thread: " . $story['title'] . "\n";
        $thread_id = create_thread($pdo, $story['title']);
        if (!$thread_id) {
            echo "Failed to create story thread: " . $story['title'] . ". Skipping.\n";
            continue;
        }
        $threads_created_count++;
        $posts_in_this_thread = 0;
        $story_post_count = count($story['posts']);
        $time_offset_base = $story_index * 3600 * 6; // Offset each story start time

        for ($i = 0; $i < $story['target_posts']; $i++) {
            if ($total_posts_created >= $total_target_posts) break 2; // Break outer loop too

            $post_data = null;
            $author = $story['protagonist'];
            $image = null;

            if ($i < $story_post_count) { // Use predefined posts first
                $post_data = $story['posts'][$i];
                $author = $post_data['author'] ?? $story['protagonist']; // Use protagonist if author not set
                $body = $post_data['body'];
                if (strpos($body, '[image_url:') !== false) {
                    preg_match('/\[image_url:(.*?)\]/', $body, $matches);
                    if (isset($matches[1])) {
                        $image = trim($matches[1]);
                        $body = str_replace($matches[0], "", $body); // Remove tag from body
                    }
                }
            } else { // Generate random filler posts for the rest
                $author = get_random_author($joke_authors);
                $body = generate_random_text($common_phrases, $ascii_arts, rand(1,2));
                // Occasionally reference earlier posts
                if ($posts_in_this_thread > 0 && rand(1,5) == 1) {
                    $ref_post = rand(1, $posts_in_this_thread);
                    $body = ">>{$ref_post} " . $body;
                }
            }

            // Simulate time passing within a thread
            $post_time = clone $current_time;
            $seconds_to_subtract = $time_offset_base + ($story['target_posts'] - $i) * rand(30, 300); // Posts appear older as i increases
            $post_time->sub(new DateInterval("PT{$seconds_to_subtract}S"));
            $formatted_post_time = $post_time->format('Y-m-d H:i:s');

            create_post_with_timestamp($pdo, $thread_id, $author, $body, $image, $formatted_post_time);
            $total_posts_created++;
            $posts_in_this_thread++;
            if ($total_posts_created % 50 == 0) echo "Created $total_posts_created posts...\n";
        }
        echo "Finished story thread: " . $story['title'] . " with $posts_in_this_thread posts.\n";
    }

    // Generate Generic Threads
    echo "Generating generic threads...\n";
    $remaining_threads_to_create = $total_target_threads - $threads_created_count;

    for ($i = 0; $i < $remaining_threads_to_create; $i++) {
        if ($total_posts_created >= $total_target_posts) break;
        if ($threads_created_count >= $total_target_threads) break;

        $title_index = $threads_created_count % count($generic_thread_titles); // Cycle through titles
        $thread_title = $generic_thread_titles[$title_index] . (floor($threads_created_count / count($generic_thread_titles)) > 0 ? " Part." . (floor($threads_created_count / count($generic_thread_titles)) +1) : "");

        echo "Creating generic thread: " . $thread_title . "\n";
        $thread_id = create_thread($pdo, $thread_title);
        if (!$thread_id) {
            echo "Failed to create generic thread: " . $thread_title . ". Skipping.\n";
            continue;
        }
        $threads_created_count++;
        $posts_in_this_thread = 0;
        $num_posts_for_this_generic_thread = rand(20, 100);
        if ($total_posts_created + $num_posts_for_this_generic_thread > $total_target_posts) {
            $num_posts_for_this_generic_thread = $total_target_posts - $total_posts_created;
        }
        if ($num_posts_for_this_generic_thread <=0) break;

        $time_offset_base = ($threads_created_count + count($stories)) * 3600 * 2; // Offset generic thread start times

        for ($j = 0; $j < $num_posts_for_this_generic_thread; $j++) {
            if ($total_posts_created >= $total_target_posts) break 2;

            $author = get_random_author($joke_authors);
            $body = generate_random_text($common_phrases, $ascii_arts, rand(1,3));
            if ($posts_in_this_thread > 0 && rand(1,5) == 1) {
                $ref_post = rand(1, $posts_in_this_thread);
                $body = ">>{$ref_post} " . $body;
            }

            $post_time = clone $current_time;
            $seconds_to_subtract = $time_offset_base + ($num_posts_for_this_generic_thread - $j) * rand(60, 600);
            $post_time->sub(new DateInterval("PT{$seconds_to_subtract}S"));
            $formatted_post_time = $post_time->format('Y-m-d H:i:s');

            create_post_with_timestamp($pdo, $thread_id, $author, $body, null, $formatted_post_time);
            $total_posts_created++;
            $posts_in_this_thread++;
            if ($total_posts_created % 50 == 0) echo "Created $total_posts_created posts...\n";
        }
        echo "Finished generic thread: " . $thread_title . " with $posts_in_this_thread posts.\n";
    }

    echo "-------------------------------------\n";
    echo "Database population finished.\n";
    echo "Total Threads Created: " . $threads_created_count . "\n";
    echo "Total Posts Created: " . $total_posts_created . "\n";
    echo "-------------------------------------\n";
}

// --- Execute ---
try {
    $pdo = get_db_connection();
    // Call with explicit wipe setting if running directly (e.g. for testing)
    // When called from index.php, $enable_wipe will be false or not provided (defaulting to false)
    if (php_sapi_name() === 'cli' && basename(__FILE__) === basename($_SERVER['PHP_SELF'])) {
        echo "Running from CLI, enabling data wipe for this run.\n";
        populate_database(
            $pdo,
            $stories,
            $generic_thread_titles,
            $common_phrases,
            $ascii_arts,
            $joke_authors,
            $total_target_threads,
            $total_target_posts,
            true // Explicitly enable wipe for CLI direct execution
        );
    }
    // If included, populate_database() is now callable without triggering itself here.
    // The index.php script will call it with $enable_wipe = false.

} catch (Exception $e) {
    echo "An error occurred: " . $e->getMessage() . "\n";
}

?>

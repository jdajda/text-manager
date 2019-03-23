<html>
<head>
<title>Text manager</title>
<link rel="stylesheet" href="https://unpkg.com/mustard-ui@latest/dist/css/mustard-ui.min.css">
<style>
    .highlight {
        background: yellow;
    }
</style>
</head>
<body>

<?php
$textUrl = $_POST['textUrl'];
$searchQuery = $_POST['searchQuery'];
if ($_POST['action']=="fetch") {
    unset($searchQuery);
}

$content = file_get_contents($textUrl);
if (isset($searchQuery) && !empty($searchQuery)) {
    $searchQueryParts = preg_split('/\s+/', $searchQuery);
    foreach ($searchQueryParts as $keyword) {
        $pos = strpos($content, $keyword, 0);
        $numberOfHits = 0;
        while ($pos!==FALSE) {
            $numberOfHits++;
            $replaceString = "<span id='$keyword-$numberOfHits' class='highlight'>" . $keyword . "</span>";
            $content = substr_replace($content, $replaceString, $pos, strlen($keyword));
            $pos = strpos($content, $keyword, $pos + strlen($replaceString));
        }
        $hits[$keyword] = $numberOfHits;
    }
}

?>

<header style="height: 200px;">
<h1>Text manager</h1>
</header>
<br>
<div class="row">
    <div class="col col-sm-5">
        <div class="panel">
            <div class="panel-body">
                <form action="index.php" method="post">   
                <h2 class="panel-title">1. Get text</h2>
                    <input type="text" placeholder="Enter the poem url" name="textUrl" value="<?php if (isset($textUrl)) echo $textUrl; ?>"><br >
                    <button type="submit" name="action" value="fetch" class="button-primary align-center">Fetch text</button>
                <h2 class="panel-title">2. Find keywords</h2>
                    <input type="text" placeholder="Enter text to be highlighted" name="searchQuery" value="<?php if (isset($searchQuery)) echo $searchQuery; ?>"><br >
                    <button type="submit" name="action" value="search" class="button-primary">Search text</button>
                </form>
                <?php
                if (isset($hits)) {
                    echo '<h2 class="panel-title">3. Check results</h2>';
                    echo '<div class="stepper">';
                    foreach ($hits as $key => $value) {
                        echo '<div class="step">';
                        echo '<p class="step-number">' . $value . '</p>' ;
                        echo '<p class="step-title"><span class="tag tag-blue">Keyword: </span><i>' . $key  . '</i></p>';
                        for ($i = 1; $i <= $value ; $i++) {
                            echo "<a href='#$key-$i' class='button-primary-outlined'>$i</a> ";
                        }
                        echo '</div>';        
                    }
                    echo '</div>';
                }
                ?>
            </div>
        </div>
    </div>

    <div class="col col-sm-7" style="padding-left: 25px;">
        <?php 
        if (isset($content) && !empty($content)) {
            echo "<pre><code>";
            echo $content;
            echo "</code></pre>";
        }
        ?>            
    </div>
</div>

</body>
</html>


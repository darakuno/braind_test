<?php
function create_annotation($text, $articleUrl) {
    $article = trim($text); //удаляем пробелы в начале
    
    if (strlen($article) > 250) {
        $article = substr($article, 0, 250); //если текст длиннее 250 симв., обрезаеи его
        
        $lastSpace = strrpos($article, ' ');  //удаляем текст до последнего пробела
        if ($lastSpace !== false) {
            $article = substr($article, 0, $lastSpace);
        }
        $article .= '...'; //добавляем многоточие в конце
    }
    $annotation = $article . ' [Читать далее: ' . $articleUrl . ']'; // Добавляем ссылку на полный текст статьи
    return $annotation;
}

echo "Добро пожаловать в автoмaтическую генерaцию aнoнсoвoгo oписaния из нaчaлa текстa стaтьи!\n";

while (true) {
    echo "\nВведите текст статьи, чтобы сгенерировать описание, или пустую строку, чтобы выйти из приложения:\n";
    $articleText = readline();
    if ($articleText == "") {
        echo "До новых встреч!";
        break;
    }
    
    while (true) {
        echo "Введите ссылку на статью:\n";
        $articleUrl = trim(fgets(STDIN));
        
        if (filter_var($articleUrl, FILTER_VALIDATE_URL)) { // Проверка на правильность введенной ссылки
            break;
        } else {
            echo "Ошибка: пожалуйста, введите корректный URL.\n"; 
        }
    }
    
    $annotation = create_annotation($articleText, $articleUrl);
    
    echo str_repeat("-", 5)." Сгенерированный анонс ".str_repeat("-", 5)."\n";
    echo $annotation."\n";
}



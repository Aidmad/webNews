<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Добавить статью</title>
        <!-- FONT AWESOME ICONS -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

        <!-- GOOGLE FONTS -->
        <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet" />
        <link rel="stylesheet" type="text/css" href="../includes/css/add-news.css">
    </head>

    <body>
        <div class="button-container">
            <!-- Left-aligned button (Back to Main) -->
            <button class="home-button" onclick="window.location.href='main.php';">На главную</button>
        </div>
        <!-- EDIT FORMS -->
        <div class="container">
            <form action="../config/saveNews.php" method="POST" enctype="multipart/form-data">
                <!-- TITLE INPUT -->
                <div class="title-input">
                    <input type="text" name="art_title" id="article-title" placeholder="Введите заголовок статьи" required autocomplete="off"/>
                </div>
                <!-- AUTHOR INPUT -->
                <div class="author-input">
                    <input type="text" name="author" id="article-author" placeholder="Введите имя автора" required />
                </div>
                <!-- DESCRIPTION INPUT -->
                <div class="desc-input">
                    <textarea name="article_desc" id="article-desc" placeholder="Введите описание" rows="5" required></textarea>
                </div>
                <!-- IMAGE UPLOAD -->
                <div class="image-upload">
                    <label for="image-upload" class="upload-label">Загрузить изображение</label>
                    <input type="file" name="image-upload" id="image-upload" accept="image/*" />
                </div>
                <!-- TEXT FORMAT -->
                <div class="options">
                    <button type="button" id="bold" class="option-button format">
                        <i class="fa-solid fa-bold"></i>
                    </button>
                    <button type="button" id="italic" class="option-button format">
                        <i class="fa-solid fa-italic"></i>
                    </button>
                    <button type="button" id="underline" class="option-button format">
                        <i class="fa-solid fa-underline"></i>
                    </button>
                    <button type="button" id="strikethrough" class="option-button format">
                        <i class="fa-solid fa-strikethrough"></i>
                    </button>
                    <button type="button" id="superscript" class="option-button format">
                        <i class="fa-solid fa-superscript"></i>
                    </button>
                    <button type="button" id="subscript" class="option-button format">
                        <i class="fa-solid fa-subscript"></i>
                    </button>
                    <!-- LIST -->
                    <button type="button" id="insertOrderedList" class="option-button">
                        <i class="fa-solid fa-list-ol"></i>
                    </button>
                    <button type="button" id="insertUnorderedList" class="option-button">
                        <i class="fa-solid fa-list"></i>
                    </button>
                    <!-- UNDO/REDO -->
                    <button type="button" id="undo" class="option-button">
                        <i class="fa-solid fa-rotate-left"></i>
                    </button>
                    <button type="button" id="redo" class="option-button">
                        <i class="fa-solid fa-rotate-right"></i>
                    </button>
                    <!-- LINK -->
                    <button type="button" id="createLink" class="adv-option-button">
                        <i class="fa-solid fa-link"></i>
                    </button>
                    <button type="button" id="unlink" class="option-button">
                        <i class="fa-solid fa-link-slash"></i>
                    </button>
                    <!-- ALIGNMENT -->
                    <button type="button" id="justifyLeft" class="option-button align">
                        <i class="fa-solid fa-align-left"></i>
                    </button>
                    <button type="button" id="justifyCenter" class="option-button align">
                        <i class="fa-solid fa-align-justify"></i>
                    </button>
                    <button type="button" id="justifyRight" class="option-button align">
                        <i class="fa-solid fa-align-right"></i>
                    </button>
                    <button type="button" id="justifyFull" class="option-button align">
                        <i class="fa-solid fa-align-justify"></i>
                    </button>
                    <button type="button" id="indent" class="option-button spacing">
                        <i class="fa-solid fa-indent"></i>
                    </button>
                    <button type="button" id="outdent" class="option-button spacing">
                        <i class="fa-solid fa-outdent"></i>
                    </button>
                    <!-- HEADINGS -->
                    <select id="formatBlock" class="adv-option-button">
                        <option value="H1">H1</option>
                        <option value="H2">H2</option>
                        <option value="H3">H3</option>
                        <option value="H4">H4</option>
                        <option value="H5">H5</option>
                        <option value="H6">H6</option>
                    </select>
                    <!-- FONT -->
                    <select id="fontName" class="adv-option-button"></select>
                    <select id="fontSize" class="adv-option-button"></select>
                    <!-- COLORS -->
                    <div class="input-wrapper">
                        <input type="color" id="foreColor" class="adv-option-button" />
                        <label for="foreColor">Font color</label>
                    </div>
                    <div class="input-wrapper">
                        <input type="color" id="backColor" class="adv-option-button" />
                        <label for="backColor">Highlight color</label>
                    </div>
                </div>
                <div id="text-input" contenteditable="true"></div>
                <!-- Hidden input to capture content from contenteditable div -->
                <input type="hidden" name="article" id="hidden-article" />
                <!-- SAVE BUTTON-->
                <button type="submit" id="save-btn">Опубликовать</button>
            </form>
        </div>
        <!-- SCRIPT FOR FORM FUNCTION-->
        <script src="../includes/js/add.js"></script>
        <script>
            // Update hidden input value on form submit
            document.querySelector('form').addEventListener('submit', function () {
                // Capture the content with all formatting (color, highlight, etc.)
                document.getElementById('hidden-article').value = document.getElementById('text-input').innerHTML;
            });
        </script>
    </body>

</html>
<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Главная</title>
        <link rel="stylesheet" type="text/css" href="../includes/css/main-style.css">
        <script src="https://cdn.jsdelivr.net/npm/vue@2/dist/vue.js"></script>
    </head>

    <body>
        <!-- TO CENTER CONTENT -->
        <div class="center-container" id="app">
            <div class="newsTitle-text">
                <p>{{ newsTitle }}</p> <!-- This text is managed by Vue.js -->
            </div>
            <div class="info-container">
                <?php
                    include '../config/db_config.php'; // include the database connection
            
                    // Define how many articles you want on each page
                    $articles_per_page = 6; // 2 rows * 3 articles per row
                    $current_page = isset($_GET['page']) ? (int) $_GET['page'] : 1; // Get the current page from the URL
                    $offset = ($current_page - 1) * $articles_per_page; // Calculate the offset for the SQL query
            
                    // Query to get total number of articles
                    $total_sql = "SELECT COUNT(*) as total FROM newstb";
                    $total_result = $conn->query($total_sql);
                    $total_row = $total_result->fetch_assoc();
                    $total_articles = $total_row['total']; // Total number of articles
            
                    // Calculate total pages
                    $total_pages = ceil($total_articles / $articles_per_page); // Total number of pages
            
                    // Query to fetch articles for the current page
                    $sql = "SELECT * FROM newstb ORDER BY id DESC LIMIT ?, ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("ii", $offset, $articles_per_page);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0) {
                        // Output data of each row
                        while ($row = $result->fetch_assoc()) {
                            echo '<a href="news.php?id=' . $row['id'] . '" class="info-box ' . ($row['id'] % 3 == 0 ? 'right' : ($row['id'] % 3 == 1 ? 'left' : 'center')) . '">';
                            echo '    <div class="image-container">';
                            echo '        <img src="' . htmlspecialchars($row["img"]) . '" alt="Image ' . $row["id"] . '">';
                            echo '    </div>';
                            echo '    <div class="text-content">';
                            echo '        <div class="title">' . htmlspecialchars($row["art_title"]) . '</div>';
                            echo '        <div class="description">' . htmlspecialchars($row["descr"]) . '</div>';
                            echo '    </div>';
                            echo '</a>';
                        }
                    } else {
                        echo "0 results";
                    }

                    $stmt->close(); // Close the statement
                    $conn->close(); // Close the database connection
                ?>
            </div>

            <!-- PAGES -->
            <?php if ($total_pages > 1): // Show pagination only if there are multiple pages ?>
                <div class="pages-container">
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <a href="?page=<?php echo $i; ?>" <?php if ($i == $current_page)
                        echo 'class="active"'; ?>>
                        <?php echo $i; ?>
                    </a>
                    <?php endfor; ?>
                </div>
                <?php endif; ?>
        </div>

        <script>
            new Vue({
                el: '#app',
                data: {
                    newsTitle: 'Новости', // You can easily manage the title here
                }
            });
        </script>
    </body>

</html>
<?php
function pagination($perPage, $currentPage, $totalItems) {
    $totalPages = ceil($totalItems / $perPage);
    $baseUrl = strtok($_SERVER['REQUEST_URI'], '?'); // работает быстрее чем эксплод?

    // чек, что страниц больше одной
    $currentPage = max(1, min($totalPages, $currentPage));

    if ($totalPages > 1) {
        $startPage = max(1, $currentPage - 2);
        $endPage = min($totalPages, $currentPage + 2);

        $queryParams = $_GET;

        // отдаёт красивую ссылку
        function generateUrl($page, $baseUrl, $queryParams) {
            $queryParams['page'] = $page;
            return htmlspecialchars($baseUrl . '?' . http_build_query($queryParams));
        }

        // ссылка на предидущую страницу
        if ($currentPage > 1) {
            echo "<a href='" . generateUrl($currentPage - 1, $baseUrl, $queryParams) . "'>&laquo;</a>";
        }

        // ссылки на 2 ближайшие страницы слева и справа
        for ($i = $startPage; $i <= $endPage; $i++) {
            $activeClass = $i == $currentPage ? "active" : "";
            echo "<a href='" . generateUrl($i, $baseUrl, $queryParams) . "' class='$activeClass'>$i</a>";
        }

        // ссылка на следующую страницу
        if ($currentPage < $totalPages) {
            echo "<a href='" . generateUrl($currentPage + 1, $baseUrl, $queryParams) . "'>&raquo;</a>";
        }

        // окошко для ввода нужной страницы
        echo "<form action='" . htmlspecialchars($baseUrl) . "' method='GET'>
                <label for='page'>Go to page:</label>
                <input type='number' id='page' name='page' min='1' max='$totalPages' value='$currentPage'>
                <button type='submit'>Go</button>";

        // бережно храним параметры запроса
        foreach ($queryParams as $key => $value) {
            if ($key !== 'page') {
                echo "<input type='hidden' name='$key' value='" . htmlspecialchars($value) . "'>";
            }
        }

        echo "</form>";
    }
}
// прям готовый модуль получился, каеф
?>

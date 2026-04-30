<?php
// ฟังก์ชันสำหรับสร้างปุ่มเปลี่ยนหน้า
function renderPagination($page, $total_pages) {
    if ($total_pages <= 1) return; // ถ้ามีหน้าเดียวไม่ต้องแสดงปุ่ม
    
    echo '<nav aria-label="Page navigation" class="mt-4">';
    echo '<ul class="pagination justify-content-center">';
    
    // ปุ่มย้อนกลับ (Previous)
    $prev = $page - 1;
    $disabled_prev = ($page <= 1) ? 'disabled' : '';
    echo '<li class="page-item ' . $disabled_prev . '"><a class="page-link" href="?page=' . $prev . '">ก่อนหน้า</a></li>';

    // วนลูปแสดงเลขหน้า
    for ($i = 1; $i <= $total_pages; $i++) {
        $active = ($page == $i) ? 'active' : '';
        echo '<li class="page-item ' . $active . '"><a class="page-link" href="?page=' . $i . '">' . $i . '</a></li>';
    }

    // ปุ่มถัดไป (Next)
    $next = $page + 1;
    $disabled_next = ($page >= $total_pages) ? 'disabled' : '';
    echo '<li class="page-item ' . $disabled_next . '"><a class="page-link" href="?page=' . $next . '">ถัดไป</a></li>';
    
    echo '</ul>';
    echo '</nav>';
}
?>
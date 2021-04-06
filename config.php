<?php
/////// Update your database login details here /////
$dbhost_name = "10.0.32.24"; // Your host name
$database = "sitemanager";       // Your database name
$username = "bitrix0";            // Your login userid
$password = "G{-QB!B=U]Zb41xHoT66";            // Your password
//////// End of database details of your server //////

// For development - Delete below code after dev stage
/////// Update your database login details here /////
$dbhost_name = "127.0.0.1"; // Your host name
$database = "cr";       // Your database name
$username = "root";            // Your login userid
$password = "";            // Your password
//////// End of database details of your server //////

try {
  $db = new PDO('mysql:host=' . $dbhost_name . ';charset=utf8;dbname=' . $database, $username, $password);
} catch (PDOException $e) {
  print "Error!: " . $e->getMessage() . "<br/>";
  die();
}

function getUrl()
{
  if (isset($_SERVER['REQUEST_URI'])) {
    $url = rtrim($_SERVER['REQUEST_URI'], '/');
    $url = parse_url($url, PHP_URL_PATH);

    $url = filter_var($url, FILTER_SANITIZE_URL);
    $url = explode('/', $url);

    $url = array_values(array_filter($url, function ($var) {
      return ($var !== null && $var !== false && $var !== "");
    }));

    return $url;
  }
}

function getParams()
{
  if (isset($_SERVER['REQUEST_URI'])) {
    $url = rtrim($_SERVER['REQUEST_URI'], '/');
    $query = parse_url($url, PHP_URL_QUERY);

    if (!is_null($query)) {
      parse_str($query, $queryArr);
      return $queryArr;
    } else {
      return [];
    }
  }
}

function dbBind(&$stmt, $parameter, $value, $type = null)
{
  switch (is_null($type)) {
    case is_int($value):
      $type = PDO::PARAM_INT;
      break;
    case is_bool($value):
      $type = PDO::PARAM_BOOL;
      break;
    case is_null($value):
      $type = PDO::PARAM_NULL;
      break;
    default:
      $type = PDO::PARAM_STR;
  }

  return $stmt->bindValue($parameter, $value, $type);
}

function data_get($data, $path, $default = null)
{
  $paths = explode('.', $path);

  return array_reduce($paths, function ($o, $p) use ($default) {
    if (isset($o->$p)) return (is_object($o->$p) ? (array) $o->$p : $o->$p) ?? $default;
    if (isset($o[$p])) return (is_object($o[$p]) ? (array) $o[$p] : $o[$p])  ?? $default;

    return $default;
  }, (array) $data);
}


$departments = [
  ['value' => 46, 'title' => 'KANGAROO'],
  ['value' => 79, 'title' => 'R&D-Viện nghiên cứu'],
  ['value' => 89, 'title' => 'F. KS NỘI BỘ'],
  ['value' => 196, 'title' => 'G. VP HĐQT'],
  ['value' => 293, 'title' => 'Z. EXTRANET'],
  ['value' => 308, 'title' => 'A. KHỐI CUNG ỨNG'],
  ['value' => 309, 'title' => 'B. KHỐI SẢN XUẤT'],
  ['value' => 310, 'title' => 'C. KHỐI SALES & MARKETING'],
  ['value' => 311, 'title' => 'E. BRANCHES'],
  ['value' => 313, 'title' => 'D. KHỐI HỖ TRỢ'],
  ['value' => 326, 'title' => 'Chat bots'],
  ['value' => 343, 'title' => 'CTY THÀNH VIÊN'],
  ['value' => 270, 'title' => 'Tester CNTT'],
  ['value' => 358, 'title' => 'Team GA'],
  ['value' => 359, 'title' => 'Team C&B'],
  ['value' => 360, 'title' => 'Team Tuyển dụng'],
  ['value' => 320, 'title' => 'MYA-FACTORY'],
  ['value' => 60, 'title' => 'HN_Thiết kế'],
  ['value' => 104, 'title' => 'HN_Phòng Digital Marketing'],
  ['value' => 252, 'title' => ' Coporate MKT & PR'],
  ['value' => 366, 'title' => 'Ourdoor'],
  ['value' => 205, 'title' => 'TTDVKT MIỀN Bắc'],
  ['value' => 210, 'title' => 'CALL CENTER'],
  ['value' => 235, 'title' => 'KHOLK/TTDVKT NHÀ MÁY'],
  ['value' => 333, 'title' => 'NM_Supply Chain Management'],
  ['value' => 376, 'title' => 'NM_Production'],
  ['value' => 377, 'title' => 'NM_Back & QC'],
  ['value' => 153, 'title' => 'KHO VẬN MIỀN TRUNG'],
  ['value' => 154, 'title' => 'DN_PHÒNG HCNS'],
  ['value' => 155, 'title' => 'PHÒNG KẾ TOÁN'],
  ['value' => 156, 'title' => 'DN_BACK SALES'],
  ['value' => 157, 'title' => 'DN_PHÒNG MARKETING'],
  ['value' => 158, 'title' => 'TTDVKT MIỀN TRUNG'],
  ['value' => 223, 'title' => 'DN_NH GB-NB-DL NAM MIỀN TRUNG'],
  ['value' => 225, 'title' => 'DN_NH VLXD BẮC NAM MIỀN TRUNG'],
  ['value' => 226, 'title' => 'DN_KINH DOANH MT'],
  ['value' => 324, 'title' => 'DN_PHÒNG DỰ ÁN'],
  ['value' => 325, 'title' => 'DN_NH GD-NB-DL BẮC MIỀN TRUNG'],
  ['value' => 369, 'title' => 'DN_NH GD-NB-DL-VLXD TÂY NGUYÊN'],
  ['value' => 138, 'title' => 'HCM_Phòng Hành chính-Nhân sự'],
  ['value' => 139, 'title' => 'HCM_Phòng Kế toán'],
  ['value' => 140, 'title' => 'HCM_Phòng Điều hành'],
  ['value' => 141, 'title' => 'HCM_Phòng Quảng cáo đối ngoại'],
  ['value' => 143, 'title' => 'HCM_Kho Vận'],
  ['value' => 144, 'title' => 'HCM_TTDVKT'],
  ['value' => 151, 'title' => 'HCM_Phòng dự án'],
  ['value' => 161, 'title' => 'HCM_Phòng KD Kênh MT'],
  ['value' => 284, 'title' => 'HCM_Phòng Thu Mua'],
  ['value' => 285, 'title' => 'HCM_Phòng KD Kênh GT'],
  ['value' => 331, 'title' => 'HCM_Phòng Retail Marketing'],
  ['value' => 204, 'title' => 'Miền Bắc - Gia dụng GT'],
  ['value' => 329, 'title' => 'HCM_Hành Chính'],
  ['value' => 361, 'title' => 'BP Nhân Sự'],
  ['value' => 321, 'title' => 'HCM_Phòng Điều Hành - Tro Ly'],
  ['value' => 330, 'title' => 'HCM_Outdoor-Trainning'],
  ['value' => 317, 'title' => 'Planning'],
  ['value' => 318, 'title' => 'Sales Order'],
  ['value' => 280, 'title' => 'HCM_PHÒNG KINH DOANH - MT - ONLINE'],
  ['value' => 281, 'title' => 'HCM_PHÒNG KINH DOANH - MT -  ĐIỆN MÁY XANH'],
  ['value' => 282, 'title' => 'HCM_PHÒNG KINH DOANH - MT - CAO PHONG'],
  ['value' => 283, 'title' => 'HCM_PHÒNG KINH DOANH - MT - NGUYỄN KIM'],
  ['value' => 290, 'title' => 'HCM_PHÒNG KINH DOANH - MT - HYPER'],
  ['value' => 380, 'title' => 'Kho Sơn La'],
  ['value' => 230, 'title' => 'KỸ THUẬT'],
  ['value' => 231, 'title' => 'KHO LINH KIỆN_HÀ NỘI'],
  ['value' => 232, 'title' => 'KHO VẬN'],
  ['value' => 233, 'title' => 'HỖ TRỢ TTBHUQ 1'],
  ['value' => 234, 'title' => 'HÕ TRƠ TTBHUQ 2'],
  ['value' => 253, 'title' => 'Trade Marketing'],
  ['value' => 365, 'title' => 'TeleSales'],
  ['value' => 84, 'title' => 'VLXD - Kênh GT'],
  ['value' => 82, 'title' => 'Điện lạnh miền Bắc _GT'],
  ['value' => 255, 'title' => 'Điện lạnh miền Bắc _MT'],
  ['value' => 83, 'title' => 'TBNB-GT MIỀN BẮC'],
  ['value' => 54, 'title' => 'HN_Phòng Công nghệ thông tin'],
  ['value' => 59, 'title' => 'Kế toán - Accounting'],
  ['value' => 159, 'title' => 'Sale Operation'],
  ['value' => 207, 'title' => 'Tài chính - Finance'],
  ['value' => 286, 'title' => 'HCM_PHÒNG KINH DOANH - GT - NHÀ BẾP'],
  ['value' => 287, 'title' => 'HCM_PHÒNG KINH DOANH GT - GIA DỤNG'],
  ['value' => 288, 'title' => 'HCM_PHÒNG KINH DOANH GT - NGÀNH VẬT LIỆU XÂY DỰNG'],
  ['value' => 291, 'title' => 'HCM_KINH DOANH GT - NGÀNH ĐIỆN LẠNH'],
  ['value' => 299, 'title' => 'PHÒNG KINH DOANH GT - GIA DỤNG - MIỀN ĐÔNG'],
  ['value' => 300, 'title' => 'PHÒNG KINH DOANH GT - GIA DỤNG - HCM+MTÂY'],
  ['value' => 301, 'title' => 'PG-Miền Bắc'],
  ['value' => 302, 'title' => 'PG-Miền Nam'],
  ['value' => 303, 'title' => 'PG-Miền Trung'],
  ['value' => 292, 'title' => 'PG'],
  ['value' => 294, 'title' => 'SaleReps'],
  ['value' => 304, 'title' => 'Gia dụng'],
  ['value' => 305, 'title' => 'TBNB'],
  ['value' => 306, 'title' => 'VLXD'],
  ['value' => 73, 'title' => 'Phòng Logistics'],
  ['value' => 74, 'title' => 'Ban Mua sắm'],
  ['value' => 243, 'title' => 'Xuất nhập khẩu'],
  ['value' => 274, 'title' => 'Phòng Xuất khẩu'],
  ['value' => 64, 'title' => 'VP QC QUỐC TẾ'],
  ['value' => 72, 'title' => 'KHO VẬN MIỀN BẮC'],
  ['value' => 76, 'title' => 'NHÀ MÁY HƯNG YÊN'],
  ['value' => 69, 'title' => 'MARKETING'],
  ['value' => 71, 'title' => 'Trung tâm DVKT'],
  ['value' => 246, 'title' => 'Retail Marketing'],
  ['value' => 315, 'title' => 'NGÀNH HÀNG'],
  ['value' => 316, 'title' => 'KÊNH'],
  ['value' => 65, 'title' => 'INDONESIA'],
  ['value' => 66, 'title' => 'LÀO'],
  ['value' => 67, 'title' => 'MYANMAR'],
  ['value' => 77, 'title' => 'CN MIỀN TRUNG'],
  ['value' => 78, 'title' => 'CN MIỀN NAM'],
  ['value' => 182, 'title' => 'CAMPUCHIA'],
  ['value' => 56, 'title' => 'Hành chính Nhân sự - GAHR'],
  ['value' => 88, 'title' => 'Pháp chế - Legal'],
  ['value' => 268, 'title' => 'TCKT - IT'],
  ['value' => 81, 'title' => 'Gia dụng'],
  ['value' => 249, 'title' => 'Vật liệu xây dựng'],
  ['value' => 254, 'title' => 'Điện lạnh'],
  ['value' => 259, 'title' => 'TBNB'],
  ['value' => 183, 'title' => 'Dự án Miền Bắc'],
  ['value' => 261, 'title' => 'Key Account- Miền Bắc'],
  ['value' => 314, 'title' => 'MT'],
  ['value' => 356, 'title' => 'Nhà Thuốc'],
  ['value' => 375, 'title' => 'Online Miền Bắc'],
  ['value' => 279, 'title' => 'HCM_BP Retail Marketing'],
  ['value' => 131, 'title' => 'NM_Phòng QC'],
  ['value' => 335, 'title' => 'NM_Phòng QA'],
  ['value' => 134, 'title' => 'NM_Phòng Cung ứng Nhà máy'],
  ['value' => 136, 'title' => 'NM_Kho Linh kiện- vật tư sản xuất'],
  ['value' => 297, 'title' => 'NM_Phòng Kế hoạch điều độ'],
  ['value' => 334, 'title' => 'NM_Dự án Phát triển SX & XNK'],
  ['value' => 374, 'title' => 'NM_Kho thành phẩm'],
  ['value' => 336, 'title' => 'NM_QC OEM'],
  ['value' => 337, 'title' => 'NM_OQA'],
  ['value' => 344, 'title' => 'WELLSYS'],
  ['value' => 353, 'title' => 'Kangaroo Tech'],
  ['value' => 130, 'title' => 'NM_Phân xưởng Điện lanh'],
  ['value' => 160, 'title' => 'NM_Xưởng sản xuất BNN_NLMT'],
  ['value' => 295, 'title' => 'NM_Xưởng sản xuất Ro'],
  ['value' => 296, 'title' => 'NM_Xưởng sản xuất Inox'],
  ['value' => 132, 'title' => 'NM_Phòng Kế toán'],
  ['value' => 133, 'title' => 'NM_Phòng Hành chính-Nhân sự'],
  ['value' => 208, 'title' => 'NM_Kỹ thuật'],
  ['value' => 332, 'title' => 'NM_Quality Assurance'],
];

$currentDepartment = array_values(array_filter($departments, function ($item) {
  return $item['title'] === 'TCKT - IT';
}))[0] ?? [];

<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();

/*
09/05/2019 - Bổ sung menu Tài chính
);*/
$dbUser = CUser::GetByID($USER->GetID());
global $USER;
echo "[" . $USER->GetID() . "] (" . $USER->GetLogin() . ") " . $USER->GetFullName();

$department = '';
if ($arUser = $dbUser->Fetch()) {
    if (isset($arUser["UF_DEPARTMENT"])) {
        if (!is_array($arUser["UF_DEPARTMENT"]))
            $arUser["UF_DEPARTMENT"] = array(
                $arUser["UF_DEPARTMENT"]
            );

        foreach ($arUser["UF_DEPARTMENT"] as $v)
            $arUserDepartmentId[] = $v;
        $department = current($arUser["UF_DEPARTMENT"]);

    }
}

$aMenuLinks = Array(
    Array(
        "Thêm mới",
        "add/",
        Array(),
        Array(),
        ""
    ),
    Array(
        "Nhập chỉ tiêu",
        "edit/",
        Array(),
        Array(),
        ""
    )
);


?>
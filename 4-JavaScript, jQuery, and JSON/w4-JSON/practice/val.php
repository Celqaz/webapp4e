<?php
// 验证加载的表单
function validateData()
{
    // 验证加载的基础表单
    if (strlen($_POST['first_name']) < 1 || strlen($_POST['last_name']) < 1 || strlen($_POST['email']) < 1
  ||  strlen($_POST['headline']) < 1 || strlen($_POST['summary']) < 1) {
        return 'All values are required';
    }

    if (strpos($_POST['email'], '@') === false) {
        return 'Email address must contain @';
    }
    // 验证Pos
    for ($i=1; $i <= 9  ; $i++) {
        // error_log("Login success ".$_POST['email']);
        if (! isset($_POST['year'.$i])) {
            continue;
        }
        if (! isset($_POST['desc'.$i])) {
            continue;
        }
        $year = $_POST['year'.$i];
        $desc = $_POST['desc'.$i];
        if (strlen($year) ==0 || strlen($desc) == 0) {
            return('All fields are required');
        }

        if (! is_numeric($year)) {
            return('Position year must be numeric');
        }
    }
    //验证 Edu
    for ($i=1; $i <= 9  ; $i++) {
        if (! isset($_POST['edu_year'.$i])) {
            continue;
        }
        if (! isset($_POST['edu_school'.$i])) {
            continue;
        }
        $edu_year = $_POST['edu_year'.$i];
        $edu_school = $_POST['edu_school'.$i];

        if (strlen($edu_year) == 0 || strlen($edu_school) == 0) {
            return('All fields are required');
        }

        if (! is_numeric($edu_year)) {
            return('Education year must be numeric');
        }
    }
    return true;
}

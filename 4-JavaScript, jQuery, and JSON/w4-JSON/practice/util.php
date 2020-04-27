<?php
# 出现Flash Message
function flashMsg()
{
    if (isset($_SESSION["success"])) {
        echo('<p style="color:green">'.htmlentities($_SESSION["success"])."</p>\n");
        unset($_SESSION["success"]);
    };
    if (isset($_SESSION["error"])) {
        echo('<p style="color:red">'.htmlentities($_SESSION["error"])."</p>\n");
        unset($_SESSION["error"]);
    };
}

# 插入数据-Profile
function insertProfileData($pdo)
{
    $sql = "INSERT INTO profile(user_id, first_name, last_name, email, headline, summary)
  VALUES (:user_id, :first_name, :last_name, :email, :headline, :summary)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(
        array(
      ':user_id' => $_SESSION['user_id'],
      ':first_name' => $_POST['first_name'],
      ':last_name' => $_POST['last_name'],
      ':email' => $_POST['email'],
      ':headline' => $_POST['headline'],
      ':summary' => $_POST['summary']
    )
    );
}

# 插入数据
    # Get the Primary key
    // $profile_id = $pdo->lastInsertId();
function insertDataPos($pdo, $profile_id)
{
    # loop to insert
    $rank = 1;
    for ($i=1; $i <= 9  ; $i++) {
        if (! isset($_POST['year'.$i])) {
            continue;
        }
        if (! isset($_POST['desc'.$i])) {
            continue;
        }
        $year = $_POST['year'.$i];
        $desc = $_POST['desc'.$i];

        $sql = 'INSERT INTO `position`(`profile_id`, `rank`, `year`, `description`)
        VALUES (:profile_id, :rank, :year, :desc)';
        $stmt = $pdo->prepare($sql);
        $stmt -> execute(
            array(
               ':profile_id' => $profile_id,
               ':rank' => $rank,
               ':year' => $year,
               ':desc' => $desc
               )
        );
        $rank++;
    }# for loop
};
// insert EDU
function insertDataEdu($pdo)
{
    $rank = 1;
    for ($i=1; $i <= 9  ; $i++) {
        if (! isset($_POST['edu_school'.$i])) {
            continue;
        }
        $edu_school = $_POST['edu_school'.$i];
        error_log($edu_school);
        // 根据用户输入的institution name ，查找数据库中是否存在该institution，没有就添加，有就do nothing
        $stmt =$pdo->prepare('SELECT institution_id FROM institution WHERE name = :name');
        $stmt -> execute(
            array(
          ':name' => $edu_school
        )
        );
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        error_log($row);
        // 判断是否存在,返回 institution_id
        if ($row=== false) {
            $insertIns = $pdo->prepare('INSERT INTO institution(name) VALUES (:name)');
            $insertIns ->execute(
                array(
              ':name' => $edu_school
            )
            );
            $institution_id = $pdo->lastInsertId();
        } else {
            $institution_id = $row['institution_id'];
        }
        // error_log

//         $sql = "INSERT INTO profile(user_id, first_name, last_name, email, headline, summary)
// VALUES (:user_id, :first_name, :last_name, :email, :headline, :summary)";
//         $stmt = $pdo->prepare($sql);
//         $stmt->execute(
//             array(
//     ':user_id' => $_SESSION['user_id'],
//     ':first_name' => $_POST['first_name'],
//     ':last_name' => $_POST['last_name'],
//     ':email' => $_POST['email'],
//     ':headline' => $_POST['headline'],
//     ':summary' => $_POST['summary']
//   )
//         );
    }
}

# 加载表单信息
function loadPos($pdo, $profile_id)
{
    $sql = 'SELECT * FROM position WHERE profile_id = :profile_id ORDER BY rank';
    $stmt = $pdo -> prepare($sql);
    $stmt -> execute(
        array(
      ':profile_id' => $profile_id
    )
    );
    $positions = array();
    while ($row = $stmt -> fetch(PDO::FETCH_ASSOC)) {
        $positions[] = $row;
    }
    return $positions;
}


// # 验证用户输入有效性
// function validateProfile()
// {
//     if (strlen($_POST['first_name']) < 1 || strlen($_POST['last_name']) < 1 || strlen($_POST['email']) < 1
//   ||  strlen($_POST['headline']) < 1 || strlen($_POST['summary']) < 1) {
//         return 'All values are required';
//     }
//
//     if (strpos($_POST['email'], '@') === false) {
//         return 'Email address must contain @';
//     }
//     return  true;
// }
// // 验证加载的表单
// function validatePos()
// {
//     for ($i=1; $i <= 9  ; $i++) {
//         // error_log("Login success ".$_POST['email']);
//         if (! isset($_POST['year'.$i])) {
//             continue;
//         }
//         if (! isset($_POST['desc'.$i])) {
//             continue;
//         }
//         if (! isset($_POST['edu_year'.$i])) {
//             continue;
//         }
//         if (! isset($_POST['edu_school'.$i])) {
//             continue;
//         }
//         $year = $_POST['year'.$i];
//         $desc = $_POST['desc'.$i];
//         $edu_year = $_POST['edu_year'.$i];
//         $edu_school = $_POST['edu_school'.$i];
//
//         if (strlen($year) ==0 || strlen($desc) == 0 || strlen($edu_year) == 0 || strlen($edu_school) == 0) {
//             return('All fields are required');
//         }
//
//         if (! is_numeric($year)) {
//             return('Position year must be numeric');
//         }
//         if (! is_numeric($edu_year)) {
//             return('Education year must be numeric');
//         }
//         error_log($year.'--'.$desc.'--'.$edu_year.'--'.$edu_school.'\n');
//     }
//     return  true;
// }
// // 验证Edu
// function validateEdu()
// {
//     for ($i=1; $i <= 9  ; $i++) {
//         if (! isset($_POST['edu_year'.$i])) {
//             continue;
//         }
//         if (! isset($_POST['edu_school'.$i])) {
//             continue;
//         }
//         $edu_year = $_POST['edu_year'.$i];
//         $edu_school = $_POST['edu_school'.$i];
//
//         if (strlen($edu_year) == 0 || strlen($edu_school) == 0) {
//             return('All fields are required');
//         }
//
//         if (! is_numeric($edu_year)) {
//             return('Education year must be numeric');
//         }
//     }
//     return  true;
// }

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
function insertDataPro($pdo)
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

# 插入数据-Position
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
//插入数据-Education
function insertDataEdu($pdo, $profile_id)
{
    $rank = 1;
    for ($i=1; $i <= 9  ; $i++) {
        if (! isset($_POST['edu_school'.$i])) {
            continue;
        }
        $edu_year = $_POST['edu_year'.$i];
        $edu_school = $_POST['edu_school'.$i];

        // error_log($edu_school);
        // 根据用户输入的institution name ，查找数据库中是否存在该institution，没有就添加，有就do nothing
        $stmt =$pdo->prepare('SELECT institution_id FROM institution WHERE name = :name');
        $stmt -> execute(
            array(
          ':name' => $edu_school
        )
        );
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        // error_log($row);
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
        $stmt= $pdo->prepare('INSERT INTO `education`(`profile_id`, `institution_id`, `rank` ,`year`)
        VALUES (:profile_id, :institution_id, :rank, :year)');
        $stmt->execute(
            array(
                      ':profile_id' => $profile_id,
                      ':institution_id' => $institution_id,
                      ':rank' => $rank,
                      ':year' =>$edu_year
                    )
        );
        $rank++;
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
// view-查找edu信息
function resumeInfo($pdo, $profile_id)
{
    //profile
    $stmt_pro = $pdo->prepare("SELECT profile_id, user_id,first_name, last_name, email, headline, summary FROM profile where profile_id= :xyz");
    $stmt_pro->execute(array(":xyz" => $profile_id));
    $arr['pro'] = $stmt_pro->fetch(PDO::FETCH_ASSOC);
    //pos
    $stmt_pos = $pdo->prepare("SELECT year,description FROM position where profile_id= :xyz");
    $stmt_pos->execute(array(":xyz" =>  $profile_id));
    $arr['pos'] = $stmt_pos->fetchAll(PDO::FETCH_ASSOC);
    //edu
    $stmt_edu  = $pdo->prepare("SELECT institution.name,education.year
  FROM institution INNER JOIN education ON education.institution_id = institution.institution_id WHERE education.profile_id = :profile_id ORDER BY rank");
    $stmt_edu -> execute(array(':profile_id' =>  $profile_id));
    $arr['edu']= $stmt_edu->fetchAll(PDO::FETCH_ASSOC);

    // $arr.append()
    return $arr;
}
// edit-更新信息
function info_update($pdo, $profile_id)
{
    // update profile
    $sql = "UPDATE profile SET user_id = :user_id, first_name = :first_name, last_name = :last_name,
            email = :email, headline = :headline, summary = :summary
            WHERE profile_id = :profile_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(
        array(
        ':user_id' => $_SESSION['user_id'],
        ':first_name' => $_POST['first_name'],
        ':last_name' => $_POST['last_name'],
        ':email' => $_POST['email'],
        ':headline' => $_POST['headline'],
        ':summary' => $_POST['summary'],
        ':profile_id' => $profile_id
      )
    );
    // Clear out the old position entries
    $sql = 'DELETE FROM position WHERE (profile_id = :profile_id)';
    $stmt = $pdo -> prepare($sql);
    $stmt -> execute(
        array(
        ':profile_id' => $profile_id
      )
    );
    insertDataPos($pdo, $profile_id);
    // Clear out the old education entries
    $sql = 'DELETE FROM education WHERE (profile_id = :profile_id)';
    $stmt = $pdo -> prepare($sql);
    $stmt -> execute(
        array(
        ':profile_id' => $profile_id
      )
    );
    insertDataEdu($pdo, $profile_id);
    return true;
}

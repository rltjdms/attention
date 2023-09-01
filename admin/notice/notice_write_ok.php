<?php
  session_start(); 
  require_once $_SERVER['DOCUMENT_ROOT'].'/attention/admin/inc/dbcon.php';
  // var_dump($_POST);

  // //관리자 검사
  // if(!isset($_SESSION['AUID'])){
  //   echo "<script>
  //   alert('권한이 없습니다');
  //   history.back();
  //   </script>";
  // }
  
  $title = $_POST['title'];
  $content = $_POST['content'];
  $date = date('Y-m-d');
  $filename = '';

  if($_FILES['file']['name']){
    //파일 사이즈 검사
    if($_FILES['file']['size']> 10240000){
      echo "<script>
        alert('10MB 이하만 첨부할 수 있습니다.');
        history.back();
      </script>";
      exit;
    }
    if(isset($_FILES['file'])){
      //파일 업로드
      $save_dir = $_SERVER['DOCUMENT_ROOT']."/attention/pdata/notice/"; //파일 저장 경로
      $filename = $_FILES['file']['name']; //파일명
      $ext = pathinfo($filename, PATHINFO_EXTENSION); //확장자
      $newfilename = date("YmdHis").substr(rand(), 0,6); //파일명 datetime명으로 변경
      $notice_file = $newfilename.".".$ext; //변경된 파일명 + 확장자
      
      if(move_uploaded_file($_FILES['file']['tmp_name'], $save_dir.$notice_file)){  
        $notice_file = "/attention/pdata/notice/".$notice_file;
      } else{
        echo "<script>
          alert('파일 첨부 실패!');    
          history.back();            
        </script>";
      }
    }
  }

  $sql = "INSERT INTO notice (title, content, date, file) 
  VALUES ('{$title}','{$content}','{$date}','{$filename}')";

  if($mysqli->query($sql) === true){
    echo "<script>
      alert('게시물 작성이 완료 되었습니다.');
      location.href='/attention/admin/notice/notice.php';</script>";
  } else{
    echo "<script>
      alert('게시물 작성 실패');
      history.back();</script>";
  }

?>
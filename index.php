<?php
	session_start();
	
	if($_GET[w]=="!MyPage"){
		die(header("Location: /settings"));
	}
	
	// Settings
	$conn = mysqli_connect("localhost", "username", "userpass", "dbname");
	mysqli_set_charset($conn,"utf8");
	if(!$conn){
		$cantsubdb = true;
		echo "<script> alert('보조 DB 서버에 접속할 수 없습니다.\\n일부 기능이 동작하지 않습니다.'); </script>";
	}
	$sql = "SELECT * FROM settings WHERE ip = '$_SERVER[REMOTE_ADDR]'";
	$res = mysqli_query($conn, $sql);
	$cnt = mysqli_num_rows($res);
	
	if($cnt){
		$settings = mysqli_fetch_array($res);
	} else {
		$sql = "SELECT * FROM settings WHERE ip = '0.0.0.0'";
		$res = mysqli_query($conn, $sql);
		$settings = mysqli_fetch_array($res);
	}
	$sqlref = "SELECT * FROM settings WHERE ip = '0.0.0.0'";
	$resref = mysqli_query($conn, $sqlref);
	$settingsref = mysqli_fetch_array($resref);
	if($_GET[settings]!=""){
		$_GET[w] = "!MyPage";
		$sql = "SELECT * FROM settings WHERE ip = '$_SERVER[REMOTE_ADDR]'";
		if($_GET[autover]!=""){
			$res = mysqli_query($conn, $sql);
			$cnt = mysqli_num_rows($res);
			if(!$cnt){
				$sql = "INSERT INTO settings(`ip`, `docVersion`, `docAutoLoad`, `imgAutoLoad`) VALUES ";
				$sql .= "('".$_SERVER[HTTP_CF_CONNECTING_IP]."', '170327', '1', '1')";
				mysqli_query($conn, $sql);
			}
			
			switch($_GET[autover]){
				case "161031":
					$docVersion = 161031;
					break;
				case "160829":
					$docVersion = 160829;
					break;
				case "160728":
					$docVersion = 160728;
					break;
				case "160627":
					$docVersion = 160627;
					break;
				case "160530":
					$docVersion = 160530;
					break;
				case "160425":
					$docVersion = 160425;
					break;
				case "160329":
					$docVersion = 160329;
					break;
				case "160229":
					$docVersion = 160229;
					break;
				default:
					$docVersion = 170327;
			}
			
			$sql = "UPDATE settings SET docVersion = '$docVersion', docAutoLoad = '1', imgAutoLoad = '1', enableAds = '1', enableViewCount = '1' WHERE ip = '$_SERVER[REMOTE_ADDR]'";
			mysqli_query($conn, $sql);
			
			die(header("Location: /w/TheWiki:홈"));
		}
		if($_GET[create]!=""){
			$sql = "SELECT * FROM settings WHERE ip = '$_SERVER[REMOTE_ADDR]'";
			$res = mysqli_query($conn, $sql);
			$cnt = mysqli_num_rows($res);
			if(!$cnt){
				$sql = "INSERT INTO settings(`ip`, `docVersion`, `docAutoLoad`, `imgAutoLoad`) VALUES ";
				$sql .= "('$_SERVER[REMOTE_ADDR]', '170327', '1', '1')";
				mysqli_query($conn, $sql);
			}
			
			die(header("Location: /settings"));
		}
		if($_GET[apply]!=""){
			switch($_POST[Ads]){
				case "on":
					$enableAds = 1;
					break;
				default:
					$enableAds = 0;
			}
			switch($_POST[Notice]){
				case "on":
					$enableNotice = 1;
					break;
				default:
					$enableNotice = 0;
			}
			switch($_POST[docAL]){
				//case "on":
				//	$docAutoLoad = 1;
				//	break;
				default:
					$docAutoLoad = 1;
			}
			switch($_POST[imgAL]){
				case "on":
					$imgAutoLoad = 1;
					break;
				default:
					$imgAutoLoad = 0;
			}
			switch($_POST[docVersion]){
				case "161031":
					$docVersion = 161031;
					break;
				case "160829":
					$docVersion = 160829;
					break;
				case "160728":
					$docVersion = 160728;
					break;
				case "160627":
					$docVersion = 160627;
					break;
				case "160530":
					$docVersion = 160530;
					break;
				case "160425":
					$docVersion = 160425;
					break;
				case "160329":
					$docVersion = 160329;
					break;
				case "160229":
					$docVersion = 160229;
					break;
				default:
					$docVersion = 170327;
			}
			switch($_POST[ViewCount]){
				case "on":
					$enableViewCount = 1;
					break;
				default:
					$enableViewCount = 0;
			}
			
			$sql = "UPDATE settings SET docVersion = '$docVersion', docAutoLoad = '$docAutoLoad', imgAutoLoad = '$imgAutoLoad', enableAds = '$enableAds', enableNotice = '$enableNotice', enableViewCount = '$enableViewCount' WHERE ip = '$_SERVER[REMOTE_ADDR]'";
			mysqli_query($conn, $sql);
			
			die(header("Location: /settings"));
		}
	}
	
	if($cantsubdb){
		$settings[docVersion] = "170327";
		$settings[docAutoLoad] = 1;
		$settings[imgAutoLoad] = 1;
		$settings[enableAds] = 1;
		$settings[enableNotice] = 1;
		$settings[enableViewCount] = 1;
	}
	
	if($_GET[w]=="!ADReport"){
		$settings[docVersion] = $settingsref[docVersion];
	}
	if($_GET[search]!=""){
		die(header("Location: /w/".$_GET[search]));
	}
	if($_GET[go]!=""){
		die(header("Location: /w/".$_GET[go]));
	}
	if($_GET[w]==""){
		die(header("Location: /w/TheWiki:홈"));
	}
	$w = $_GET[w];
	
	if(count(explode(":", $_GET[w]))>1){
		$tp = explode(":", $_GET[w]);
		switch($tp[0]){
			case "틀":
				$namespace = '1';
				break;
			case "분류":
				$namespace = '2';
				break;
			case "파일":
				$namespace = '3';
				break;
			case "사용자":
				$namespace = '4';
				break;
			case "나무위키":
				$namespace = '6';
				break;
			case "휴지통":
				$namespace = '8';
				break;
			case "TheWiki":
				$namespace = '10';
				break;
			case "이미지":
				$namespace = '11';
				break;
			default:
				$namespace = '0';
		
		}
		if($namespace>0){
			$w = str_replace($tp[0].":", "", implode(":", $tp));
		}
	}
	
	if($namespace==1){
		$tw = explode(",", str_replace("_(SSS)_", "#", $w));
		$w = $tw[0];
		foreach($tw as $key => $val){
			if($key>0){
				$twval = explode("=", $val);
				$override[trim($twval[0])] = trim(str_replace($twval[0]."=", "", $val));
			}
		}
	}
	
	if($_GET[raw]==""){
		$wiki_count = sha1($_GET[w]);
?>
<html>
	<meta charset="utf-8"/>
	<title><?=$_GET[w]?></title>
	<meta name="viewport" content="user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, width=device-width"/>
	<meta http-equiv="x-ua-compatible" content="ie=edge"/>
	<meta name="robots" content="index,follow">
	<meta property="og:type" content="website">
	<link rel="stylesheet" href="/namuwiki/css/jquery-ui.min.css"/>
	<link rel="stylesheet" href="/namuwiki/css/bootstrap.min.css"/>
	<link rel="stylesheet" href="/namuwiki/css/ionicons.min.css"/>
	<link rel="stylesheet" href="/namuwiki/css/katex.min.css"/>
	<link rel="stylesheet" href="/namuwiki/css/flag-icon.min.css"/>
	<link rel="stylesheet" href="/namuwiki/css/diffview.css"/>
	<link rel="stylesheet" href="/namuwiki/css/nprogress.css"/>
	<link rel="stylesheet" href="/namuwiki/css/bootstrap-fix.css"/>
	<link rel="stylesheet" href="/namuwiki/css/layout.css"/>
	<link rel="stylesheet" href="/namuwiki/css/wiki.css"/>
	<link rel="stylesheet" href="/namuwiki/css/discuss.css"/>
	<link rel="stylesheet" href="/namuwiki/css/dark.css"/>
	<!--[if (!IE)|(gt IE 8)]><!-->
	<script type="text/javascript" src="/namuwiki/js/jquery-2.1.4.min.js"></script>
	<!--<![endif]-->
	<!--[if lt IE 9]>
		<script type="text/javascript" src="/namuwiki/js/jquery-1.11.3.min.js?1444428364"></script>
		<script type="text/javascript" src="/namuwiki/js/html5.js?1444428364"></script>
		<script type="text/javascript" src="/namuwiki/js/respond.min.js?1444428364"></script>
		<![endif]-->
	<script type="text/javascript" src="/namuwiki/js/jquery.lazyload.min.js"></script>
	<script type="text/javascript" src="/namuwiki/js/jquery-ui.min.js"></script>
	<script type="text/javascript" src="/namuwiki/js/tether.min.js"></script>
	<script type="text/javascript" src="/namuwiki/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="/namuwiki/js/jquery.pjax.js"></script>
	<script type="text/javascript" src="/namuwiki/js/nprogress.js"></script>
	<script type="text/javascript" src="/namuwiki/js/dateformatter.js"></script>
	<script type="text/javascript" src="/namuwiki/js/namu.js"></script>
	<script type="text/javascript" src="/namuwiki/js/wiki.js"></script>
	<script type="text/javascript" src="/namuwiki/js/edit.js"></script>
	<script type="text/javascript" src="/namuwiki/js/discuss.js"></script>
	<script type="text/javascript" src="/namuwiki/js/theseed.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			$("#userDiscussAlert").html('궁금하신 점이 있으신가요? <a href="/request/">기술지원</a>을 요청해보세요.');
			var addque = true;
			function urlencode(str) {
				str = (str + '').toString();
				return encodeURIComponent(str)
					.replace('%2F', '/')
					.replace(/!/g, '%21')
					.replace(/'/g, '%27')
					.replace(/\(/g, '%28')
					.replace(/\)/g, '%29')
					.replace(/\*/g, '%2A')
					.replace(/%20/g, '+');
			}
			
			$("#addque").click(function(){
				if(addque){
					addque = false;
					$.get("/queue/"+urlencode('<?=$_GET[w]?>'), function(Data){
						Data = JSON.parse(Data);
						if(Data.result.status=="success"){
							$("#userDiscussAlert").html('대기열에 추가했습니다. 곧 문서가 갱신됩니다.');
						} else {
							$("#userDiscussAlert").html('대기열에 추가하지 못했습니다. 같은 문제가 지속되면 <a href="/request/">기술지원</a>을 요청해보세요.');
							addque = true;
						}
					});
				}
			});
		});
		
		
	</script>
<?php if($settings[enableViewCount]){ ?>
	<script type="text/javascript">
		$(document).ready(function(){
			$.get("/count/<?=$wiki_count?>", function(Data){
				$(".viewcount").html('문서 조회수 : '+Data+'회');
			});
		});
	</script>
<?php	} ?>
	</head>
	<body class="senkawa hide-sidebar fixed-size fixed-1300">
		<script type="text/javascript" src="/namuwiki/js/layout.js?e4665c6b"></script>
		<div class="navbar-wrapper">
			<nav class="navbar navbar-dark bg-inverse navbar-static-top">
				<a class="navbar-brand wiki-logo" href="/w/TheWiki:홈"></a>
				<ul class="nav navbar-nav">
					<li class="nav-item">
						<a class="nav-link" itemprop="url" target="_top" href="/Recent" title="최근 변경">
							<span class="icon ion-compass"></span>
							<span class="icon-title">최근 변경</span>
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" itemprop="url" target="_top" href="/RecentDiscuss" title="최근 토론">
							<span class="icon ion-android-textsms"></span>
							<span class="icon-title">최근 토론</span>
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" itemprop="url" target="_top" href="/Random" title="랜덤 문서">
							<span class="icon ion-shuffle"></span>
							<span class="icon-title">랜덤 문서</span>
						</a>
					</li>
					<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" href="#" title="특수 기능" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
							<span class="icon ion-ios-gear"></span>
							<span class="icon-title">특수 기능</span>
						</a>
						<div class="dropdown-menu right">
							<a class="dropdown-item" itemprop="url" href="/request/">
								<span class="icon ion-help"></span>
								<span class="icon-title">기술 지원</span>
							</a>
							<a class="dropdown-item" href="/Upload.php">
								<span class="icon ion-android-upload"></span>
								<span class="icon-title">파일 올리기</span>
							</a>
							<a class="dropdown-item" target="_blank" href="/LICENSE">
								<span class="icon ion-pricetag"></span>
								<span class="icon-title">라이선스</span>
							</a>
						</div>
					</li>
					<li class="nav-item">
					</li>
<?php		if($_SESSION[job]!=""){ ?>
					<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" href="#" title="ADMIN" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
							<span class="icon ion-person"></span>
							<span class="icon-title">ADMIN</span>
							<span class="caret"></span>
						</a>
						<div class="dropdown-menu right">
							<a class="dropdown-item" href="/admin/block">
								<span class="icon ion-locked"></span>
								<span class="icon-title">계정 차단</span>
							</a>
							<a class="dropdown-item" href="//issue.thewiki.ga/" onclick="return false;">
								<span class="icon ion-alert"></span>
								<span class="icon-title">이슈 리포트</span>
							</a>
<?php		} ?>
				</ul>
				<ul class="nav navbar-nav pull-right">
					<li class="nav-item dropdown user-menu-parent">
						<a class="nav-link dropdown-toggle user-menu" href="#" title="Member Menu" data-toggle="dropdown" aria-haspopup="true">
<?php					if($_SESSION[name]!=""){
							if(!$_SESSION[email]!=""){ $conn = mysqli_connect("localhost", "username", "userpass", "dbname"); mysqli_set_charset($conn,"utf8"); $sql = "SELECT * FROM wiki_user WHERE name = '$_SESSION[name]' LIMIT 1"; $res = mysqli_query($conn, $sql); $asr = mysqli_fetch_array($res); $_SESSION[email] = $asr[email]; } $gravatar = md5(trim($_SESSION[email])); ?>
							<img class="user-img" src="//secure.gravatar.com/avatar/<?=$gravatar?>?d=retro">
<?php					} else { ?>
							<span class="icon ion-person"></span>
<?php					} ?>
						</a>
						<div class="dropdown-menu user-dropdown right">
<?php					if($_SESSION[name]!=""){ ?>
							<div class="dropdown-item user-info">
								<div class="user-info">
									<div><?=$_SESSION[name]?></div>
									<div><?=$_SESSION[job]?></div>
								</div>
							</div>
							<div class="dropdown-divider"></div>
							<a class="dropdown-item" itemprop="url" href="/settings">설정</a>
							<a class="dropdown-item" href="/MyPage.php">계정 설정</a>
							<div class="dropdown-divider"></div>
							<a class="dropdown-item" href="/w/내문서:<?=$_SESSION[name]?>">내 문서</a>
							<a class="dropdown-item" href="/userinfo/<?=$_SESSION[name]?>/contributions">문서 기여 목록</a>
							<a class="dropdown-item" href="/userinfo/<?=$_SESSION[name]?>/discuss">토론 기여 목록</a>
							<div class="dropdown-divider"></div>
							<a class="dropdown-item" href="/logout">로그아웃</a>
<?php					} else { ?>
							<div class="dropdown-item user-info">
								<div class="user-info">
									<div><?=$_SERVER[REMOTE_ADDR]?></div>
									<div>Viewer</div>
								</div>
							</div>
							<div class="dropdown-divider"></div>
							<a class="dropdown-item" itemprop="url" href="/settings">설정</a>
							<a class="dropdown-item" href="/login.php">로그인</a>
<?php					} ?>
						</div>
					</li>
				</ul>
				<form class="form-inline navbar-form search-box-parent">
					<div class="input-group search-box">
						<input type="text" id="searchInput" class="form-control" placeholder="Search" tabindex="1">
						<span class="input-group-btn right-search-btns">
							<button id="goBtn" class="btn btn-secondary" type="button">
								<span class="icon ion-arrow-right-c"></span>
							</button>
						</span>
					</div>
				</form>
			</nav>
		</div>
		<div class="content-wrapper">
			<article class="container-fluid wiki-article">
<?php if($settings[enableNotice]){ ?>
				<div class="alert alert-info fade in last" id="userDiscussAlert" role="alert">
					Loading... <?=$_GET[w]?>
				</div>
<?php }
		ob_flush();
		flush();
	}
	
	// The Wiki 반영
	$thewiki = mysqli_connect("localhost", "username", "userpass", "dbname");
	mysqli_set_charset($thewiki,"utf8");
	if(!$thewiki){
		echo "<script> alert('메인 DB 서버에 접속할 수 없습니다.\\n주요 기능이 동작하지 않습니다.'); </script>";
		$cantmaindb = true;
	}
	$date = date("Y-m-d H:i:s");
	
	$sql = "SELECT * FROM wiki_contents_moved WHERE p_namespace = '$namespace' AND p_title = binary('$w') ORDER BY no DESC LIMIT 1";
	$res = mysqli_query($thewiki, $sql);
	$moved_arr = mysqli_fetch_array($res);
	
	if($moved_arr[title]!=""){
		$namespace3 = $moved_arr['namespace'];
		$w2 = $moved_arr[title];
		switch($namespace3){
			case "1":
				$namespace2 = '틀:';
				break;
			case "2":
				$namespace2 = '분류:';
				break;
			case "3":
				$namespace2 = '파일:';
				break;
			case "4":
				$namespace2 = '사용자:';
				break;
			case "6":
				$namespace2 = '나무위키:';
				break;
			case "8":
				$namespace2 = '휴지통:';
				break;
			case "10":
				$namespace2 = 'TheWiki:';
				break;
			case "11":
				$namespace2 = '이미지:';
				break;
			default:
				$namespace2 = '';
		}
		die("<meta http-equiv='Refresh' content='0;url=/w/".$namespace2.$w2."'>");
	}
	
	$sql = "SELECT * FROM wiki_contents_moved WHERE namespace = '$namespace' AND title = binary('$w') ORDER BY no DESC LIMIT 1";
	$res = mysqli_query($thewiki, $sql);
	$moved_arr = mysqli_fetch_array($res);
	
	if($moved_arr[title]!=""){
		$namespace = $moved_arr[p_namespace];
		$w = $moved_arr[p_title];
	}
	
	if($namespace>0){
		$sql4 = "SELECT * FROM wiki_admin_data WHERE title = binary('".$tp[0].":') AND expire > '$date' ORDER BY expire DESC LIMIT 1";
		$res4 = mysqli_query($thewiki, $sql4);
		$arr4 = mysqlI_fetch_array($res4);
	}
	
	if(!$arr4[read]>0){
		$sql4 = "SELECT * FROM wiki_admin_data WHERE title = binary('$_GET[w]') AND expire > '$date' ORDER BY expire DESC LIMIT 1";
		$res4 = mysqli_query($thewiki, $sql4);
		$cnt4 = mysqli_num_rows($res4);
		if($cnt4>0){
			$arr4 = mysqli_fetch_array($res4);
		}
	}
	
	if($settings[enableViewCount]){
		$wiki_count = "<span class='viewcount'>문서 조회수 확인중...</span>";
	} else {
		$wiki_count = "<span>&nbsp;</span>";
	}
	
	if($arr4[read]>0){ ?>
				<h1 class="title">
					<a href="#" data-npjax="true"><span itemprop="name"><?=$_GET[w]?></span></a> <?php if($select_array[contents]==""){ echo "(r20".$settings[docVersion]."판)"; } ?>
				</h1>
				<p class="wiki-edit-date"><?=$wiki_count?></p>
				<div class="wiki-content clearfix">
					<div class="wiki-inner-content">
					<a href="#">The Wiki</a>에서 읽기 보호가 설정된 문서입니다.
					</div>
				</div>
				<footer>
					<p>Powered by <a href="https://github.com/koreapyj/php-namumark" target="_blank">namumark</a> | <a href="/LICENSE" target="_blank">LICENSE</a> | <a href="//github.com/dercsyong/UnofficialNamuMirror" target="_blank">Source</a></p>
<?php
	if($settings[enableAds]){ ?>
					<p>
						<!-- 광고영역 -->
					</p>
<?php	} ?>
				</footer>
			</article>
		</div>
	</body>
</html>
<?php	die();
	}
	
	$pattern = '/([0-9])+/';
	preg_match_all($pattern, $_GET[rev], $match);
	$rev = strtolower(implode('', $match[0]));
	
	if($namespace==""){
		$namespace = 0;
	}
	
	$select = "SELECT * FROM wiki_contents_data WHERE namespace = '$namespace' AND title = binary('$w') ORDER BY rev DESC LIMIT 1";
	$select_query = mysqli_query($thewiki, $select);
	$select_array = mysqli_fetch_array($select_query);
	if($select_array[contents]!=""){
		$namutext = $select_array[contents];
	}
	
	$options = array('connect' => true);
	try{
		$mongo = new MongoClient('mongodb://localhost:27017', $options);
	} catch (MongoConnectionException $ex){
		error_log($ex->getMessage());
		echo "<script> alert('mongoDB 서버에 접속할 수 없습니다.\\nThe Wiki에서 편집되지 않은 문서는 확인할 수 없습니다.'); </script>";
		$cantmongo = true;
	}
	
	if(!$cantmongo||$cantmaindb){
		// 문서내용을 불러옵니다.
		switch($settings[docVersion]){
		//	case "160229": $collection = $mongo->nisdisk->docData160229; break;
		//	case "160329": $collection = $mongo->nisdisk->docData160329; break;
		//	case "160425": $collection = $mongo->nisdisk->docData160425; break;
		//	case "160530": $collection = $mongo->nisdisk->docData160530; break;
		//	case "160627": $collection = $mongo->nisdisk->docData160627; break;
		//	case "160728": $collection = $mongo->nisdisk->docData160728; break;
		//	case "160829": $collection = $mongo->nisdisk->docData160829; break;
		//	case "161031": $collection = $mongo->nisdisk->docData161031; break;
			default: $collection = $mongo->nisdisk->docData170327; break;
		}
		
		// 0이면 못불러오는 문제 있음
		if(!$namespace){
			$query = array("namespace"=>"0", "title"=>$w);
		} else {
			$query = array("namespace"=>$namespace, "title"=>$w);
			//print_r($query);
		}
		$arr = $collection->findOne($query);
		$tparr = $arr;
		$contribution = implode("\\n", $arr[contributors]);
		
		// 문서 전체 개수
		$AllPage = $collection->count();
	}
	
	if($namutext!=""){
		$arr[text] = $namutext;
	}
	
	// 애드센스 정책
	if(count(explode("틀:성적요소", $arr[text]))>1){
		$settings[enableAds] = false;
	}
	
	if($contribution==""){
		$contribution = "기여자 정보가 없습니다";
	}
	
	if(count(explode("내문서:", $w))>1){
		$get_admin = explode("내문서:", addslashes($w));
		$get_admin_job = "SELECT job FROM wiki_admin_list WHERE user = '$get_admin[1]' AND end_date > '$date' LIMIT 1";
		$get_admin_job_res = mysqli_query($thewiki, $get_admin_job);
		$get_admin_job_arr = mysqlI_fetch_array($get_admin_job_res);
		
		$get_admin_name = "SELECT name FROM wiki_admin_job WHERE job = '$get_admin_job_arr[job]' LIMIT 1";
		$get_admin_name_res = mysqli_query($thewiki, $get_admin_name);
		$get_admin = mysqlI_fetch_array($get_admin_name_res);
	}
			if($w!="!MyPage"){ ?>
				<div class="wiki-article-menu">
					<div class="btn-group" role="group">
						<a class="btn btn-secondary" href="#bottom" onclick="alert('<?=$contribution?>'); return false;" role="button">기여자 내역</a>
						<a class="btn btn-secondary" itemprop="url" href="/history/<?=$_GET[w]?>" role="button">수정 내역</a>
						<a class="btn btn-secondary" itemprop="url" href="/edit/<?=$_GET[w]?>" role="button">편집</a>
						<a class="btn btn-secondary" itemprop="url" href="/discuss/<?=$_GET[w]?>/0" role="button">토론</a>
					</div>
				</div>
<?php		} else { ?>
				<div class="wiki-article-menu">
					<div class="btn-group" role="group">
						<a class="btn btn-secondary" href="/w/!ADReport" role="button">광고 수익금 보고서</a>
					</div>
				</div>
<?php		} ?>
				<h1 class="title">
					<a href="#" data-npjax="true"><span itemprop="name"><?=$_GET[w]?></span></a> <?php if($select_array[contents]==""){ echo "(r20".$settings[docVersion]."판)"; } if($get_admin[name]!=""){ echo "<span style='font-size:1rem;'>(".$get_admin[name].")</span>"; } ?>
				</h1>
				<p class="wiki-edit-date"><?=$wiki_count?></p>
				<div class="wiki-content clearfix">
					<div class="wiki-inner-content">
<?php
			if($w=="!MyPage"){ ?>
				<h2 class="title">
					<?=$_SERVER[REMOTE_ADDR]?> 개인 설정
				</h2>
<?php			if($settings[ip]=="0.0.0.0"){ ?>
					<h4>
						<a href="settingscreate">설정파일 생성</a>이 필요합니다.
					</h4>
<?php			} else { ?>
					<form action="settingsapply" method="post" name="settings">
						<section class="tab-content settings-section">
							<div role="tabpanel" class="tab-pane fade in active" id="siteLayout">
								<div class="form-group" id="documentVersion">
									<label class="control-label">덤프 버전</label>
									<select class="form-control setting-item" name="docVersion">
										<option value="170327">20170327</option>
										<option value="161031" <?php if($settings[docVersion]=="161031"){ echo "selected"; } ?>>20161031</option>
										<option value="160829" <?php if($settings[docVersion]=="160829"){ echo "selected"; } ?>>20160829</option>
										<option value="160728" <?php if($settings[docVersion]=="160728"){ echo "selected"; } ?>>20160728</option>
										<option value="160627" <?php if($settings[docVersion]=="160627"){ echo "selected"; } ?>>20160627</option>
										<option value="160530" <?php if($settings[docVersion]=="160530"){ echo "selected"; } ?>>20160530</option>
										<option value="160425" <?php if($settings[docVersion]=="160425"){ echo "selected"; } ?>>20160425</option>
										<option value="160329" <?php if($settings[docVersion]=="160329"){ echo "selected"; } ?>>20160329</option>
										<option value="160229" <?php if($settings[docVersion]=="160229"){ echo "selected"; } ?>>20160229</option>
									</select>
								</div>
								
								<div class="form-group" id="documentAutoLoad">
									<label class="control-label">자동으로 include 문서 읽기</label>
									<div class="checkbox">
										<label>
											<input type="checkbox" name="docAL" checked disabled> 사용
										</label>
									</div>
								</div>
								
								<div class="form-group" id="imagesAutoLoad">
									<label class="control-label">자동으로 이미지 읽기</label>
									<div class="checkbox">
										<label>
											<input type="checkbox" name="imgAL" id="needads" onclick="if(!document.settings.ads.checked){ alert('광고 보이기 기능을 사용해야 이용 가능한 기능입니다'); document.settings.needads.checked = true; document.settings.ads.checked = true; }" <?php if($settings[imgAutoLoad]){ echo "checked"; }?>> 사용
										</label>
									</div>
								</div>
								
								<div class="form-group" id="Ads">
									<label class="control-label">광고 보이기</label>
									<div class="checkbox">
										<label>
											<input type="checkbox" name="Ads" id="ads" onclick="if(document.settings.needads.checked){ alert('자동으로 이미지 읽기 기능을 사용해제 해야 합니다.'); document.settings.needads.checked = false; document.settings.ads.checked = false; }" <?php if($settings[enableAds]){ echo "checked"; }?>> 사용
										</label>
									</div>
								</div>
								
								<div class="form-group" id="Notice">
									<label class="control-label">공지사항 보이기</label>
									<div class="checkbox">
										<label>
											<input type="checkbox" name="Notice" <?php if($settings[enableNotice]){ echo "checked"; }?>> 사용
										</label>
									</div>
								</div>
								
								<div class="form-group" id="ViewCount">
									<label class="control-label">문서 조회수 보이기</label>
									<div class="checkbox">
										<label>
											<input type="checkbox" name="ViewCount" <?php if($settings[enableViewCount]){ echo "checked"; }?>> 사용
										</label>
									</div>
								</div>
								
								<div class="form-group">
									&nbsp;	<button type="submit" class="btn btn-primary" onclick="if(!document.settings.ads.checked&&document.settings.needads.checked){ alert('설정이 잘못되었습니다'); return false; }">적용</button>
								</div>
							</div>
						</section>
					</form>
<?php			} ?>
					</div>
				</div>
			
				<footer>
					<p>Powered by <a href="https://github.com/koreapyj/php-namumark" target="_blank">namumark</a> | <a href="/LICENSE" target="_blank">LICENSE</a> | <a href="//github.com/dercsyong/UnofficialNamuMirror" target="_blank">Source</a></p>
<?php
	if($settings[enableAds]){ ?>
					<p>
						<!-- 광고영역 -->
					</p>
<?php	} ?>
				</footer>
			</article>
		</div>
	</body>
</html>
<?php	die(); }
	if(defined("isdeleted")){
		die('<hr>이 문서는 삭제되었습니다.<hr><a href="/edit/'.$_GET[w].'" target="_top">새로운 문서 만들기</a> &nbsp; | &nbsp; <a id="addque">나무위키에서 가져오기</a></div></div><footer><p>Powered by <a href="https://github.com/koreapyj/php-namumark" target="_blank">namumark</a> | <a href="/LICENSE" target="_blank">LICENSE</a> | <a href="//github.com/dercsyong/UnofficialNamuMirror" target="_blank">Source</a></p></footer></article></div></body></html>');
	}
	
	// 파일 문서
	if($namespace=="3"){
		die('<h3>파일 문서는 나무위키로 리다이렉트 됩니다.<br>나무위키에서 이미지가 변경되었을 경우 <a href="/request/">기술지원</a>을 통해 문의해주시기 바랍니다.</h3><meta http-equiv="Refresh" content="3;url=//namu.wiki/w/'.$_GET[w].'"><script> alert("파일 문서는 나무위키로 리다이렉트 됩니다. 나무위키에서 이미지가 변경되었을 경우 기술지원을 통해 문의해주시기 바랍니다."); </script>');
	}
	//print_r($tparr);
	if($tparr[text]!=""&&$arr[text]==""){
		$arr[text] = $tparr[text];
		echo "<script> alert('The Wiki에서 수정된 버전이 손상된 것 같습니다. 기술지원을 통해 문의해주시기 바랍니다.'); alert('DB 덤프 버전의 문서를 읽어옵니다.); </script>";
	}
	
	if($arr[text]!=""){
		// 분류 문서
		if($namespace=="2"&&$settings[docVersion]==$settingsref[docVersion]){
			$sql = "SELECT * FROM category WHERE target = '$w'";
			$res = mysqli_query($conn, $sql);
			$arr_category = mysqli_fetch_array($res);
			
			$root_directory = explode(",", $arr_category[root]);
			$list_document = explode(",", $arr_category[title]);
			
			$sql = "SELECT * FROM category WHERE target != '$w' AND root LIKE '%".$w."%'";
			$res = mysqli_query($conn, $sql);
			while($arr_root=mysqli_fetch_array($res)){
				$x++;
				$low_directory[$x] = $arr_root[target];
			}
			
			$arr2 = "= 상위 분류 =
";
			foreach($root_directory as $key => $value){
				if($value!=""&&$value!=$w){
					$value = str_replace("_(NISDISKBAN)_", ",", $value);
					$arr2 = $arr2."[[:분류:".$value."]]{{{#!html <br>}}}";
				}
			}
			$arr2 = $arr2."
= 하위 분류 =
";
			foreach($low_directory as $key=>$value){
				if($value!=""&&$value!=$w){
					$value = str_replace("_(NISDISKBAN)_", ",", $value);
					$arr2 = $arr2."[[:분류:".$value."]]{{{#!html <br>}}}";
				}
			}
			$arr2 = $arr2."
= 분류된 문서 =
";
			foreach($list_document as $key=>$value){
				$value = str_replace("_(NISDISKBAN)_", ",", $value);
				$tp = explode(":", $value);
				if($tp[0]=="파일"){
					$value = ":".$value;
				}
				if($tp[0]=="틀"||mb_substr($value, 0, 1, "UTF-8")=="틀"){
					if(mb_substr($value, 1, 1, "UTF-8")!=":"){
						$value = "틀:".mb_substr($value, 1, mb_strlen($value, "UTF-8"), "UTF-8");
					}
				}
				if($tp[0]!="분류"&&$tp[0]!="사용자"){
					$arr2 = $arr2."[[".$value."]]{{{#!html <br>}}}";
				}
			}
			$arr[text] = $arr2."
= 분류 설명 =
".$arr[text];
		}
		
		require_once("namumark.php");
		if($namespace==3){
			$arr[text] = "[[".$_GET[w]."]]".$arr[text];
		}
		if($namespace==11){
			$arr[text] = "[[".$_GET[w]."]]\n".$arr[text];
		}
		// 하위문서 링크
		$arr[text] = str_replace("[[/", "[[".$_GET[w]."/", $arr[text]);
		
		// #!wiki style 문법 우회 적용
		$arr[text] = str_replace("}}}", "_(HTMLE)_", $arr[text]);
		$htmlstart = explode('{{{#!wiki style="', $arr[text]);
		for($x=1;$x<count($htmlstart);$x++){
			$style = reset(explode('"', $htmlstart[$x]));
			$arr[text] = str_replace('{{{#!wiki style="'.$style.'"', '{{{#!html <div style="'.$style.'">}}}', $arr[text]);
			
			$loop = explode("_(HTMLE)_", next(explode('{{{#!html <div style="'.$style.'">}}}', $arr[text])));
			$check = true;
			$z = 0;
			while($check){
				if(count(explode("{{{", $loop[$z]))>1){
					$z = $z + count(explode("{{{", $loop[$z])) - 1;
				} else {
					$arr[text] = str_replace($loop[$z-1]."_(HTMLE)_".$loop[$z]."_(HTMLE)_", $loop[$z-1]."_(HTMLE)_".$loop[$z]."{{{#!html </div>}}}", $arr[text]);
					$check = false;
				}
				if($z>count($loop)){
					$check = false;
				}
			}
		}
		$arr[text] = str_replace("</div>}}}_(HTMLE)_", "</div>}}}{{{#!html </div>}}}", $arr[text]);
		
		// [[XXX|[[XXX]]]] 문법 우회 적용
		$filestart = explode('|[[파일:', $arr[text]);
		for($x=0;$x<count($filestart)-1;$x++){
			$include = end(explode("[[", $filestart[$x]));
			$filelink = "파일:".reset(explode("]]", $filestart[$x+1]));
			
			if(substr($include, 0, 7)=="http://"||substr($include, 0, 8)=="https://"||substr($include, 0, 2)=="//"){
				$change = '_(RINKTOSTART)_'.$include.'_(RINKTOMIDDLE)_[['.$filelink.']]_(RINKTOEND)_';
			} else {
				$change = '_(RINKTOSTART)_/w/'.$include.'_(RINKTOSELFMIDDLE)_[['.$filelink.']]_(RINKTOEND)_';
			}
			$arr[text] = str_replace("[[".$include."|[[".$filelink."]]]]", $change, $arr[text]);
		}
		
		// 작업마무리
		$arr[text] = str_replace("_(HTMLS)_", "{{{", $arr[text]);
		$arr[text] = str_replace("_(HTMLE)_", "}}}", $arr[text]);
		$arr[text] = str_replace("_(HTMLSTART)_", "{{{#!html", $arr[text]);
		$arr[text] = str_replace("_(NAMUMIRRORHTMLSTART)_", "{{{#!html <div style=", $arr[text]);
		$arr[text] = str_replace("_(NAMUMIRRORHTMLEND)_", "}}}", $arr[text]);
		$arr[text] = str_replace("_(NAMUMIRRORDAASH)_", "'", $arr[text]);
		$arr[text] = str_replace("﻿#", "#", $arr[text]);
		$tmparr = $arr[text];
		
		// #!folding 문법 우선 적용
		$foldingstart = explode('{{{#!folding ', $arr[text]);
		for($z=1;$z<count($foldingstart);$z++){
			$foldingcheck = true;
			$foldopentemp = reset(explode("
", $foldingstart[$z]));
			if(count(explode("#!end}}}", $foldingstart[$z]))>1){
				$foldingtemp = str_replace("#!end}}}", "_(FOLDINGEND)_", $foldingstart[$z]);
				$foldingdatatemp = next(explode($foldopentemp, reset(explode("_(FOLDINGEND)_", $foldingtemp))));
				$md5 = md5(rand(1,10).$foldingdatatemp);
				$foldopen[$md5] = $foldopentemp;
				$foldingdata[$md5] = $foldingdatatemp;
				$arr[text] = str_replace("{{{#!folding ".$foldopentemp.$foldingdatatemp."#!end}}}", "_(FOLDINGSTART)_".$md5."_(FOLDINGSTART2)_ _(FOLDINGDATA)_".$md5."_(FOLDINGDATA2)_ _(FOLDINGEND)_", $arr[text]);
			}
		}
		
		$arr[text] = str_replace("﻿||", "||", $arr[text]);
		
		// noredirect 지원
		if($noredirect){
			$arr[text] = "{{{".$arr[text]."}}}";
		}
		
		// [datetime] [PageCount] 지원
		$arr[text] = str_replace("[datetime]", date("Y-m-d H:i:s"), $arr[text]);
		
		// MySQLWikiPage와는 달리 PlainWikiPage의 첫 번째 인수로 위키텍스트를 받습니다.
		$wPage = new PlainWikiPage($arr[text]);
		
		// NamuMark 생성자는 WikiPage를 인수로 받습니다.
		$wEngine = new NamuMark($wPage);
		
		// 위키링크의 앞에 붙을 경로를 prefix에 넣습니다.
		$wEngine->prefix = "/w";
		$wPrint = $wEngine->toHtml();
		
		// #!folding
		if($foldingcheck){
			$wPrint = str_replace('_(FOLDINGEND)_', '</div></dd></dl>', $wPrint);
			
			$getmd5 = explode("_(FOLDINGDATA)_", $wPrint);
			for($xz=1;$xz<count($getmd5);$xz++){
				$mymd5 = reset(explode("_(FOLDINGDATA2)_", $getmd5[$xz]));
				$wPrint = str_replace('_(FOLDINGSTART)_'.$mymd5.'_(FOLDINGSTART2)_', '<dl class="wiki-folding"><dt><center>'.$foldopen[$mymd5].'</center></dt><dd style="display: none;"><div class="wiki-table-wrap">', $wPrint);
				
				$fPage = new PlainWikiPage($foldingdata[$mymd5]);
				$fEngine = new NamuMark($fPage);
				$fEngine->prefix = "/w";
				$fPrint = $fEngine->toHtml();
				
				$wPrint = str_replace('<div class="wiki-table-wrap"> _(FOLDINGDATA)_'.$mymd5.'_(FOLDINGDATA2)_ </div>', '<div class="wiki-table-wrap"> '.$fPrint.' </div>', $wPrint);
			}
		}
		
		$wPrint = str_replace('_(RINKTOSTART)_', '<a href="', $wPrint);
		$wPrint = str_replace('_(RINKTOMIDDLE)_', '" target="_blank">', $wPrint);
		$wPrint = str_replace('_(RINKTOSELFMIDDLE)_', '" target="_self">', $wPrint);
		$wPrint = str_replace('_(RINKTOEND)_', '</a>', $wPrint);
		
		// 이미지 queue 지원
		$wPrint = str_replace("[IMGQUEUE]", $IMGQUEUE, $wPrint);
		
		$createDivButton = explode('_(AJAXINCLUDE)_', $wPrint);
		for($x=1;$x<=count($createDivButton);$x++){
			// read information
			$xc = explode("_(AJAXINCLUDESUB)_", reset(explode("_(AJAXINCLUDEEND)_", $createDivButton[$x])));
			$namespace = $xc[0];
			$document = $xc[1];
			$fulldocument = str_replace("#", "_(SSS)_", $xc[2]);
			$xd = md5($namespace.$document.rand(1,5));
			
			if($settings[docAutoLoad]){
				$echo = '<script type="text/javascript"> $(document).ready(function(){ $.get("/raw1/'.str_replace('%', '%25', $fulldocument).'", function(data){ $(".ajax_include_'.$xd.'").html(data); }); }); </script>';
				$echo .= '<div class="ajax_include_'.$xd.'"><table class="wiki-table" style=""><tbody><tr><td style="background-color:#93C572; text-align:center;"><p><span class="wiki-size size-1"><font color="006400">[include('.str_replace("_(SSS)_", "#", $fulldocument).')] 문서 읽는중</font></span></p></td></tr></tbody></table></div>';
			} else {
				$echo = '<script type="text/javascript"> $(document).ready(function(){ enableincludeajax_'.$xd.' = true; $(".ajax_include_'.$xd.'").click(function(){ if(enableincludeajax_'.$xd.'){ enableincludeajax_'.$xd.' = false; $.get("/w/'.$fulldocument.'&raw=1", function(data){ $(".ajax_include_'.$xd.'").html(data); }); } }); }); </script>';
				$echo .= '<div class="ajax_include_'.$xd.'"><table class="wiki-table" style=""><tbody><tr><td style="background-color:#93C572; text-align:center;"><p><span class="wiki-size size-1"><font color="006400">[include('.str_replace("_(SSS)_", "#", $fulldocument).')] 문서 읽기</font></span></p></td></tr></tbody></table></div>';
			}
			$wPrint = str_replace("_(AJAXINCLUDE)_".$namespace."_(AJAXINCLUDESUB)_".$document."_(AJAXINCLUDESUB)_".str_replace("_(SSS)_", "#", $fulldocument)."_(AJAXINCLUDEEND)_", $echo, $wPrint);
		}
		
		foreach($wPrint2 as $key=>$value){
			$find = "_(SCRIPTBYPASS_".$key.")_";
			$wPrint = str_replace($find, $value, $wPrint);
		}
		
		foreach($override as $key => $val){
			$wPrint = str_replace('@'.$key.'@', $val, $wPrint);
		}
		
		if($_GET[raw]==""){
			echo str_replace("<br> <br>", "", $wPrint);
		} else if($_GET[raw]=="1"){
			die(reset(explode('<div class="wiki-category">', $wPrint)));
		} else {
			die("<xmp>".$arr[text]."</xmp>");
		}
	} else {
		if($namespace=="11"){
			$sql = "SELECT * FROM file WHERE name = '$_GET[w]'";
			$res = mysqli_query($conn, $sql);
			$cnt = mysqli_num_rows($res);
			if($cnt>0){
				require_once("namumark.php");
				$arr[text] = "[[".$_GET[w]."]]";
				// MySQLWikiPage와는 달리 PlainWikiPage의 첫 번째 인수로 위키텍스트를 받습니다.
				$wPage = new PlainWikiPage($arr[text]);
				
				// NamuMark 생성자는 WikiPage를 인수로 받습니다.
				$wEngine = new NamuMark($wPage);
				
				// 위키링크의 앞에 붙을 경로를 prefix에 넣습니다.
				$wEngine->prefix = "/w";
				$wPrint = $wEngine->toHtml();
				echo $wPrint;
			} else { ?>
				업로드된 이미지가 아닙니다.<br><a href='/Upload.php' target='_top'>이미지 업로드</a></div></div><footer><p><img alt="크리에이티브 커먼즈 라이선스" style="border-width: 0;" src="/namuwiki/cc-by-nc-sa-2.0-88x31.png"></p><p>Powered by <a href="https://github.com/koreapyj/php-namumark" target="_blank">namumark</a> | <a href="/LICENSE" target="_blank">LICENSE</a> | <a href="//github.com/dercsyong/UnofficialNamuMirror" target="_blank">Source</a></p></footer></article></div></body></html>
<?php			die();
			}
		}
?><!-- 구글 검색창 -->
<?php
		$cURLs = "http://ac.search.naver.com/nx/ac?_callback=result&q=".urlencode($_GET[w])."&q_enc=UTF-8&st=100&frm=nv&r_format=json&r_enc=UTF-8&r_unicode=0&t_koreng=1&ans=1";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $cURLs);
		curl_setopt($ch, CURLOPT_HEADER, 0); 
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		curl_setopt($ch, CURLOPT_SSLVERSION, 1);
		curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$result = curl_exec($ch);
		curl_close($ch);
		
		$result = reset(explode('"]]', next(explode('[["', $result))));
		$result = explode('"],["', $result);
		
		foreach($result as $key=>$value){
			$title_list .= "<a href='/w/".$value."'>".$value."</a> | ";
		}
		$title_list = "| ".$title_list;
		
		echo '<br><hr>저장된 문서가 아닙니다.<br>Google 맞춤검색에서 비슷한 문서가 있는지 검색해보세요.<hr><a href="/edit/'.$_GET[w].'" target="_top">새로운 문서 만들기</a> &nbsp; | &nbsp; <a id="addque">나무위키에서 가져오기</a>';
		if(count($result)>1){
			echo '<hr><br>이런 문서들이 있을 수 있습니다. 확인해보세요!<br>'.$title_list;
		}
		die('</div></div><footer><p>Powered by <a href="https://github.com/koreapyj/php-namumark" target="_blank">namumark</a> | <a href="/LICENSE" target="_blank">LICENSE</a> | <a href="//github.com/dercsyong/UnofficialNamuMirror" target="_blank">Source</a></p></footer></article></div></body></html>');
	}
?>
					</div>
				</div>
			
				<footer>
					<p><img alt="크리에이티브 커먼즈 라이선스" style="border-width: 0;" src="/namuwiki/cc-by-nc-sa-2.0-88x31.png"></p>
					<p>이 저작물은 <a href="https://namu.wiki/" target="_blank">나무위키</a>에서 저장되고 <a href="/" target="_blank">The Wiki</a>에서 수정된 것으로, <a rel="license" target="_blank" href="//creativecommons.org/licenses/by-nc-sa/2.0/kr/">CC BY-NC-SA 2.0 KR</a> 에 따라 이용할 수 있습니다. (단, 라이선스가 명시된 일부 문서 및 삽화 제외)<br/>기여하신 문서의 저작권은 각 기여자에게 있으며, 각 기여자는 기여하신 부분의 저작권을 갖습니다.</p>
					<p>Powered by <a href="https://github.com/koreapyj/php-namumark" target="_blank">namumark</a> | <a href="/LICENSE" target="_blank">LICENSE</a> | <a href="//github.com/dercsyong/UnofficialNamuMirror" target="_blank">Source</a></p>
<?php
	if($settings[enableAds]){ ?>
					<p>
						<!-- 광고 영역 -->
					</p>
<?php	} ?>
				</footer>
			</article>
		</div>
	</body>
</html>
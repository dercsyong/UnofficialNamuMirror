<?php
	if($_GET[complete]!=""){
		$cURLs = "https://namu.wiki/complete/".urlencode($_GET[complete]);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $cURLs);
		curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER[HTTP_USER_AGENT]);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_REFERER, "https://namu.wiki/w/나무위키:대문");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_VERBOSE, 0);
		$cURLs = curl_exec($ch);
		curl_close($ch);
		die($cURLs);
	}
	if($_GET[search]!=""){
		header("Location: /w/".$_GET[search]);
		die();
	}
	if($_GET[go]!=""){
		header("Location: /w/".$_GET[go]);
		die();
	}
	if($_GET[w]==""){
		header("Location: /w/나무위키:대문");
		die();
	}
	$w = $_GET[w];
	
	if(count(explode(":", $_GET[w]))>1){
		$tp = explode(":", $_GET[w]);
		switch($tp[0]){
			case "틀":
				$namespace = 1;
				break;
			case "분류":
				$namespace = 2;
				break;
			case "파일":
				$namespace = 3;
				break;
			case "사용자":
				$namespace = 4;
				break;
			case "나무위키":
				$namespace = 6;
				break;
			case "휴지통":
				$namespace = 8;
				break;
			default:
				$namespace = 0;
		
		}
		if($namespace>0){
			$w = str_replace($tp[0].":", "", implode(":", $tp));
		}
	}
	
	if($namespace==1){
		$tw = explode(",", $w);
		$w = $tw[0];
		foreach($tw as $key => $val){
			if($key>0){
				$twval = explode("=", $val);
				$override[trim($twval[0])] = trim(str_replace($twval[0]."=", "", $val));
			}
		}
	}
	if($_GET[raw]==""){
?>
<html>
	<meta charset="utf-8"/>
	<title>Unofficial NIS</title>
	<meta name="viewport" content="user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, width=device-width"/>
	<meta http-equiv="x-ua-compatible" content="ie=edge"/>
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
	<script type="text/javascript" src="/namuwiki/js/layout.js"></script>
	<!--script type="text/javascript">
		// 초기 인덱싱 오류를 해결하기 위함 ==> 쓸모없음
		$(document).ready(function(){
			$("div.wiki-inner-content").html('<p>Loading...</p>');
			$.get("/?w=w&raw=1", function(data){ $("div.wiki-inner-content").html(data);});
		});
	</script-->
	<body class="senkawa hide-sidebar fixed-size fixed-1300">
		<div class="navbar-wrapper">
			<nav class="navbar navbar-dark bg-inverse navbar-static-top">
				<a class="navbar-brand wiki-logo" href="/w/%EB%82%98%EB%AC%B4%EC%9C%84%ED%82%A4:%EB%8C%80%EB%AC%B8"></a>
				<ul class="nav navbar-nav">
					<li class="nav-item">
						<a class="nav-link" target="_blank" href="//namu.wiki/RecentChanges" title="최근 변경">
							<span class="icon ion-compass"></span>
							<span class="icon-title">최근 변경</span>
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" target="_blank" href="//namu.wiki/RecentDiscuss" title="최근 토론">
							<span class="icon ion-android-textsms"></span>
							<span class="icon-title">최근 토론</span>
						</a>
					</li>
					<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" href="#" title="특수 기능" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
							<span class="icon ion-ios-gear"></span>
							<span class="icon-title">특수 기능</span>
							<span class="caret"></span>
						</a>
						<div class="dropdown-menu right">
							<a class="dropdown-item" target="_blank" href="https://board.namu.wiki/" data-npjax="true">
								<span class="icon ion-clipboard"></span>
								<span class="icon-title">게시판</span>
							</a>
							<div class="dropdown-divider"></div>
							<a class="dropdown-item" target="_blank" href="//namu.wiki/NeededPages">
								<span class="icon ion-coffee"></span>
								<span class="icon-title">작성이 필요한 문서</span>
							</a>
							<a class="dropdown-item" target="_blank" href="//namu.wiki/OrphanedPages">
								<span class="icon ion-sad-outline"></span>
								<span class="icon-title">고립된 문서</span>
							</a>
							<a class="dropdown-item" target="_blank" href="//namu.wiki/OldPages">
								<span class="icon ion-pause"></span>
								<span class="icon-title">편집된 지 오래된 문서</span>
							</a>
							<a class="dropdown-item" target="_blank" href="//namu.wiki/ShortestPages">
								<span class="icon ion-heart-broken"></span>
								<span class="icon-title">내용이 짧은 문서</span>
							</a>
							<a class="dropdown-item" target="_blank" href="//namu.wiki/LongestPages">
								<span class="icon ion-star"></span>
								<span class="icon-title">내용이 긴 문서</span>
							</a>
							<a class="dropdown-item" target="_blank" href="//namu.wiki/BlockHistory">
								<span class="icon ion-eye-disabled"></span>
								<span class="icon-title">차단 내역</span>
							</a>
							<a class="dropdown-item" target="_blank" href="//namu.wiki/RandomPage">
								<span class="icon ion-shuffle"></span>
								<span class="icon-title">RandomPage</span>
							</a>
							<a class="dropdown-item" target="_blank" href="//namu.wiki/Upload">
								<span class="icon ion-android-upload"></span>
								<span class="icon-title">파일 올리기</span>
							</a>
							<a class="dropdown-item" target="_blank" href="//namu.wiki/SearchImage">
								<span class="icon ion-image"></span>
								<span class="icon-title">외부 이미지 참조 검색</span>
							</a>
							<a class="dropdown-item" target="_blank" href=" /License">
								<span class="icon ion-pricetag"></span>
								<span class="icon-title">라이선스</span>
							</a>
						</div>
					</li>
					<li class="nav-item">
					</li>
				</ul>
				<ul class="nav navbar-nav pull-right">
					<li class="nav-item dropdown user-menu-parent">
						<a class="nav-link dropdown-toggle user-menu" href="#" title="Member Menu" data-toggle="dropdown" aria-haspopup="true">
							<img class="user-img" src="//secure.gravatar.com/avatar/a794780f78de70b869926f9c7ce6b361?d=retro">
						</a>
						<div class="dropdown-menu user-dropdown right">
							<div class="dropdown-item user-info">
								<div class="user-info">
									<div><?=$_SERVER[REMOTE_ADDR]?></div>
									<div>Viewer</div>
								</div>
							</div>
							<div class="dropdown-divider"></div>
							<a class="dropdown-item" target="_blank" href="//namu.wiki/contribution/ip/<?=$_SERVER[REMOTE_ADDR]?>/document">내 문서 기여 목록</a>
							<a class="dropdown-item" target="_blank" href="//namu.wiki/contribution/ip/<?=$_SERVER[REMOTE_ADDR]?>/discuss">내 토론 기여 목록</a>
							<div class="dropdown-divider"></div>
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
<?php
	}
	
	// MongoDB 접속
	$mongo = new MongoDB\Driver\Manager('mongodb://localhost:27017');
	
	// 문서내용을 불러옵니다.
	// 0이면 못불러오는 문제 있음
	if(!$namespace){
		$query = new MongoDB\Driver\Query(array("namespace"=>"0", "title"=>$w));
	} else {
		$query = new MongoDB\Driver\Query(array("namespace"=>$namespace, "title"=>$w));
	}
	$docData = $mongo->executeQuery('nisdisk.docData', $query)->toArray();
	
	$arr[_id] = $docData[0]->_id;
	$arr[text] = $docData[0]->text;
	$contribution = implode("\\n", $docData[0]->contributors);
	
	// 문서 전체 개수
	$AllPage = 931029; // $collection->count()
	
	if($contribution==""){
		$contribution = "기여자 정보가 없습니다";
	}
	
	if(!$_GET[raw]){
?>
			<article class="container-fluid wiki-article">
				<div class="wiki-article-menu">
					<div class="btn-group" role="group">
						<a class="btn btn-secondary" href="#bottom" onclick="alert('<?=$contribution?>'); return false;" role="button">기여자 내역</a>
						<a class="btn btn-secondary" href="//nisdisk.ga/index.php?db=nisdisk&collection=docData&id=<?=$arr[_id]?>" target="_blank" role="button">DB 정보</a>
						<a class="btn btn-secondary" href="//bug.wiki.nisdisk.ga/" target="_blank" role="button">버그 신고</a>
					</div>
				</div>
				<h1 class="title">
					<a href="#" data-npjax="true"><?=$_GET[w]?></a> (r20170327판)
				</h1>
				<div class="wiki-content clearfix">
					<div class="wiki-inner-content">
<?php
	}
	
	if($arr[text]!=""){
		require_once("namumark.php");
		if($namespace==3){
			$arr[text] = "[[".$_GET[w]."]]".$arr[text];
		}
		
		// ver무식 namumark 적용안되는 것들 처리
		// #!wiki style 문법 우회 적용
		$arr[text] = str_replace("}}}", "_(HTMLE)_", $arr[text]);
		$htmlstart = explode('{{{#!wiki style="', $arr[text]);
		for($x=1;$x<count($htmlstart);$x++){
			$style = reset(explode('"', $htmlstart[$x]));
			$arr[text] = str_replace('{{{#!wiki style="'.$style.'"', '{{{#!html <div style="'.$style.'">}}}', $arr[text]);
			
			$exist[$x] = count(explode("{{{", reset(explode("_(HTMLE)_", next(explode('{{{#!html <div style="'.$style.'">}}}', $arr[text]))))))-1;
			$total = array_sum($exist);
			$explode = explode("_(HTMLE)_", $arr[text]);
			$arr[text] = str_replace($explode[$total]."_(HTMLE)_", $explode[$total]."{{{#!html </div>}}}", $arr[text]);
		}
		
		// [[XXX|[[XXX]]]] 문법 우회 적용
		$filestart = explode('|[[파일:', $arr[text]);
		for($x=0;$x<count($filestart)-1;$x++){
			$include = end(explode("[[", $filestart[$x]));
			$filelink = "파일:".reset(explode("]]", $filestart[$x+1]));
			
			if(substr($include, 0, 7)=="http://"||substr($include, 0, 8)=="https://"||substr($include, 0, 2)=="//"){
				$change = '{{{#!html <a href="'.$include.'" target="_blank">}}}[['.$filelink.']]{{{#!html </a>}}}';
			} else {
				$change = '{{{#!html <a href="/w/'.$include.'" target="_self">}}}[['.$filelink.']]{{{#!html </a>}}}';
			}
			$arr[text] = str_replace("[[".$include."|[[".$filelink."]]]]", $change, $arr[text]);
		}
		
		// include 문서 존재할 경우 AJAX 처리하도록 설정
		if($_GET[raw]!="1"){
			$arr[text] = str_replace('[include(', '_(INCLUDESTART)_', $arr[text]);
			$arr[text] = str_replace('[Include(', '_(INCLUDESTART)_', $arr[text]);
			$includestart = explode('_(INCLUDESTART)_', $arr[text]);
			for($x=1;$x<=count($includestart);$x++){
				$includecontent = reset(explode(")]", $includestart[$x]));
				$includename = $includecontent;
				$namespace = 0;
				if(count(explode(":", $includecontent))>1){
					$tp = explode(":", $includecontent);
					switch($tp[0]){
						case "틀":
							$namespace = 1;
							break;
						case "분류":
							$namespace = 2;
							break;
						case "파일":
							$namespace = 3;
							break;
						case "사용자":
							$namespace = 4;
							break;
						case "나무위키":
							$namespace = 6;
							break;
						case "휴지통":
							$namespace = 8;
							break;
						default:
							$namespace = 0;
					
					}
					$includecontent = str_replace($tp[0].":", "", implode(":", $tp));
				}
				$xd = $namespace."_(AJAXINCLUDESUB)_".$includecontent."_(AJAXINCLUDESUB)_".$includename;
				$arr[text] = str_replace("_(INCLUDESTART)_".$includename.")]", "_(AJAXINCLUDE)_".$xd."_(AJAXINCLUDEEND)_", $arr[text]);
			}
			$arr[text] = str_replace('_(INCLUDESTART)_', '[include(', $arr[text]);
		}
		
		// 작업마무리
		$arr[text] = str_replace("_(HTMLS)_", "{{{", $arr[text]);
		$arr[text] = str_replace("_(HTMLE)_", "}}}", $arr[text]);
		$arr[text] = str_replace("_(HTMLSTART)_", "{{{#!html", $arr[text]);
		$arr[text] = str_replace("_(NAMUMIRRORHTMLSTART)_", "{{{#!html <div style=", $arr[text]);
		$arr[text] = str_replace("_(NAMUMIRRORHTMLEND)_", "}}}", $arr[text]);
		$arr[text] = str_replace("_(NAMUMIRRORDAASH)_", "'", $arr[text]);
		
		// [datetime] [PageCount] 지원
		$arr[text] = str_replace("[datetime]", date("Y-m-d H:i:s"), $arr[text]);
		$arr[text] = str_replace("[PageCount]", $AllPage, $arr[text]);
		
		// MySQLWikiPage와는 달리 PlainWikiPage의 첫 번째 인수로 위키텍스트를 받습니다.
		$wPage = new PlainWikiPage($arr[text]);
		
		// NamuMark 생성자는 WikiPage를 인수로 받습니다.
		$wEngine = new NamuMark($wPage);
		
		// 위키링크의 앞에 붙을 경로를 prefix에 넣습니다.
		$wEngine->prefix = "/w";
		$wPrint = $wEngine->toHtml();
		
		$createDivButton = explode('_(AJAXINCLUDE)_', $wPrint);
		for($x=1;$x<=count($createDivButton);$x++){
			// read information
			$xc = explode("_(AJAXINCLUDESUB)_", reset(explode("_(AJAXINCLUDEEND)_", $createDivButton[$x])));
			$namespace = $xc[0];
			$document = $xc[1];
			$fulldocument = $xc[2];
			$xd = md5($namespace.$document);
			
			$echo = '<script type="text/javascript"> $(document).ready(function(){ enableincludeajax_'.$xd.' = true; $("#ajax_include_'.$xd.'").click(function(){ if(enableincludeajax_'.$xd.'){ enableincludeajax_'.$xd.' = false; $.get("/?w='.$fulldocument.'&raw=1", function(data){ $("#ajax_include_'.$xd.'").html(data); }); } }); }); </script>';
			$echo .= '<div id="ajax_include_'.$xd.'"><table class="wiki-table" style=""><tbody><tr><td style="background-color:#93C572; text-align:center;"><p><span class="wiki-size size-1"><font color="006400">[include('.$fulldocument.')] 문서 읽기</font></span></p></td></tr></tbody></table></div>';
			$wPrint = str_replace("_(AJAXINCLUDE)_".$namespace."_(AJAXINCLUDESUB)_".$document."_(AJAXINCLUDESUB)_".$fulldocument."_(AJAXINCLUDEEND)_", $echo, $wPrint);
		}
		
		foreach($wPrint2 as $key=>$value){
			$find = "_(SCRIPTBYPASS_".$key.")_";
			$wPrint = str_replace($find, $value, $wPrint);
		}
		
		foreach($override as $key => $val){
			$wPrint = str_replace('@'.$key.'@', $val, $wPrint);
		}
		
		if($_GET[raw]==""){
			echo $wPrint;
		} else if($_GET[raw]=="1"){
			die(reset(explode('<div class="wiki-category">', $wPrint)));
		} else {
			die("<xmp>".$arr[text]."</xmp>");
		}
	} else {
		if($_GET[raw]!=""){
			die("");
		}
		die("저장된 문서가 아닙니다.");
	}
?>
					</div>
				</div>
			
				<footer>
					<p><img alt="크리에이티브 커먼즈 라이선스" style="border-width: 0;" src="/namuwiki/cc-by-nc-sa-2.0-88x31.png"></p>
					<p>이 저작물은 <a href="https://namu.wiki/" target="_blank">나무위키</a>에서 저장된 것이며, <a rel="license" target="_blank" href="//creativecommons.org/licenses/by-nc-sa/2.0/kr/">CC BY-NC-SA 2.0 KR</a> 에 따라 이용할 수 있습니다. (단, 라이선스가 명시된 일부 문서 및 삽화 제외)<br/>기여하신 문서의 저작권은 각 기여자에게 있으며, 각 기여자는 기여하신 부분의 저작권을 갖습니다.</p>
					<p>Powered by <a href="https://github.com/koreapyj/php-namumark" target="_blank">namumark</a> | <a href="//wiki.nisdisk.ga/LICENSE" target="_blank">LICENSE</a> | <a href="//github.com/dercsyong/UnofficialNamuMirror" target="_blank">Source</a></p>
				</footer>
			</article>
		</div>
	</body>
</html>

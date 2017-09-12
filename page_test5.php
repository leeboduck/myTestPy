
<html>
<head>
	<title>회원수첩 - 추천시스템</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no">

	<!-- <script src="jquery-1.11.3.min.js"></script> -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	
	<script type="text/javascript">
		$(document).ready(function(){

			init();

			$("#search_txt").keydown(function(e){
				if(e.which == 13) send_ajax();
			})

			$("#search_btn").click(function(){
				send_ajax();
			})

			$("div#list_box div.sub_list_box li div.card_wrap div.card_txt_box div.card_txt_sub_box div.mem_url label").click(function(){
				window.open($(this).text());	
			})

			$(window).scroll(function() {
			    if ($(window).scrollTop() > 90) {
			    	$('div#top_menu').attr('hidden',false);
			    	$('div#btn_top').fadeIn();
			    }else{
			    	$('div#top_menu').attr('hidden',true);
			    	$('div#btn_top').fadeOut();
			    }
			})

			$("div#btn_top").click(function(){
			  $( 'html, body' ).animate( { scrollTop : 0 }, 400 );
			  return false;
			})

			function send_ajax(){
				var keyword = $("#search_txt").val();
				if(!keyword) {
					alert("키워드를 입력해 주세요.");
					return 0;
				}
				$('div#loading_section').attr('hidden',false);
				$.ajax({
					// url: 'http://kunspa.dothome.co.kr/test/test.php?memno=<?php echo $_GET[memno];?>',
					url: 'http://kunspa.dothome.co.kr/test/test.php?search_keyword='+keyword,
				    // url: 'http://175.119.227.97:8080/MemAdd/recommendation.jsp?search_keyword='+keyword,
					processData: false,
				  	success: function( data, textStatus, jQxhr ){


				  		$("div#list_box").attr('hidden',false);

				  		var json_data 			= $.parseJSON( data );

						var user_data 		= json_data.userJson;
			  			var user_json 		= $.parseJSON( user_data );
			  			console.log(user_json);

						var mem_data 		= json_data.memJson;
			  			var mem_json 		= $.parseJSON( mem_data );
						var mem_list 		= mem_json.recommendationResults;
						var list_data 		= $.parseJSON( mem_list );

						var product_data 	= json_data.productJson;
			  			var product_json 	= $.parseJSON( product_data );
						var product_list 	= product_json.recommendationResults;
						var list2_data 		= $.parseJSON( product_list );

						var cluster_data 	= json_data.clusterJson;
			  			var cluster_json 	= $.parseJSON( cluster_data );
						var cluster_list	= cluster_json.recommendationResults;
						var list3_data		= $.parseJSON( cluster_list );

						var interest_data 	= json_data.interestJson;
			  			var interest_json 	= $.parseJSON( interest_data );
						var interest_list 	= interest_json.recommendationResults;
						var list4_data 		= $.parseJSON( interest_list );
						
						var mem_totalHits = mem_json.totalHits;
						var product_totalHits = product_json.totalHits;
						var cluster_totalHits = cluster_json.totalHits;
						var interest_totalHits = interest_json.totalHits;
						
						var mem_response = parseFloat(mem_json.responseTime)/1000;
						var product_response = parseFloat(product_json.responseTime)/1000;
						var cluster_response = parseFloat(cluster_json.responseTime)/1000;
						var interest_response = parseFloat(interest_json.responseTime)/1000;
						
						$('div#mem_info h2').html("소속: "+user_json.com_name+" / 회원명: "+user_json.mem_name+" / 업종: "+user_json.com_cluster+" / 생상제품키워드: "+user_json.com_tech+" /<br> 관심기술: "+user_json.inter_tech);
						
						$('div.mem_add_info').html((0<mem_totalHits)?"추천결과수: "+mem_totalHits+"회 <br />응답시간: 초":"추천결과수: 0회 <br />응답시간: 0초");
						$('div.item_add_info').html((0<product_totalHits)?"추천결과수: "+product_totalHits+"회 <br />응답시간: 초":"추천결과수: 0회 <br />응답시간: 0초");
						$('div.cluster_add_info').html((0<cluster_totalHits)?"추천결과수: "+cluster_totalHits+"회 <br />응답시간: 초":"추천결과수: 0회 <br />응답시간: 0초");
						// $('div.tech_add_info').html((0<interest_totalHits)?"추천결과수: "+interest_totalHits+"회 <br />응답시간: "+interest_response+"초":"추천결과수: 0회 <br />응답시간: 0초");
						
						for(var i=0;i<10;i++){
							// console.log(i+" "+list_data[i].mem_name);
				  			if(i>=10) break;
							//회원추천
							var la = (i<mem_totalHits)?$.parseJSON(list_data[i]):"none";
							console.log(la.inter_tech);
							$('div#mem_list ul li').eq(i).find('div.mem_name2').text((i<mem_totalHits)?la.mem_name2:""); //회원명
							$('div#mem_list ul li').eq(i).find('div.mem_name').text((i<mem_totalHits)?la.mem_name:""); //회원명
				  			$('div#mem_list ul li').eq(i).find('div.com_name').text((i<mem_totalHits)?la.com_name:""); //회사명
							
				  			$('div#mem_list ul li').eq(i).find('div.com_cluster label').text((i<mem_totalHits)?la.com_tech:""); //업종명
				  			$('div#mem_list ul li').eq(i).find('div.com_item label').text((i<mem_totalHits)?la.com_cluster:""); // 제품
							$('div#mem_list ul li').eq(i).find('div.com_tech label').text((i<mem_totalHits)?la.inter_tech:""); //기술
				  			$('div#mem_list ul li').eq(i).find('div.com_url label').text((i<mem_totalHits)?la.com_item:""); // url
				  			$('div#mem_list ul li').eq(i).find('div.mem_score label').text((i<mem_totalHits)?la.score:""); // score
							
							$('div#loading_section').attr('hidden',true);
						}
						
				  		for(var i=0;i<10;i++){
				  			// console.log(i+" "+list_data[i].mem_name);
				  			if(i>=10) break;
				  			//제품추천
							var la = (i<product_totalHits)?$.parseJSON(list2_data[i]):"none";
							$('div#item_list ul li').eq(i).find('div.mem_name2').text((i<product_totalHits)?la.mem_name2:""); //회원명
							$('div#item_list ul li').eq(i).find('div.mem_name').text((i<product_totalHits)?la.mem_name:"");  //회원명
				  			$('div#item_list ul li').eq(i).find('div.com_name').text((i<product_totalHits)?la.com_name:""); //회사명 
							
				  			$('div#item_list ul li').eq(i).find('div.com_cluster label').text((i<product_totalHits)?la.com_tech:""); //업종명
				  			$('div#item_list ul li').eq(i).find('div.com_item label').text((i<product_totalHits)?la.com_cluster:""); // 제품
							$('div#item_list ul li').eq(i).find('div.com_tech label').text((i<product_totalHits)?la.inter_tech:""); //기술
				  			$('div#item_list ul li').eq(i).find('div.com_url label').text((i<product_totalHits)?la.com_item:""); // url
				  			$('div#item_list ul li').eq(i).find('div.mem_score label').text((i<product_totalHits)?la.score:""); // score
							
							$('div#loading_section').attr('hidden',true);
				  		}
						
						for(var i=0;i<10;i++){
				  			// console.log(i+" "+list_data[i].mem_name);
				  			if(i>=10) break;
				  			//업종추천
							var la = (i<cluster_totalHits)?$.parseJSON(list3_data[i]):"none";
							$('div#cluster_list ul li').eq(i).find('div.mem_name2').text((i<cluster_totalHits)?la.mem_name2:""); //회원명
							$('div#cluster_list ul li').eq(i).find('div.mem_name').text((i<cluster_totalHits)?la.mem_name:"");  //회원명
				  			$('div#cluster_list ul li').eq(i).find('div.com_name').text((i<cluster_totalHits)?la.com_name:""); //회사명
							
				  			$('div#cluster_list ul li').eq(i).find('div.com_cluster label').text((i<cluster_totalHits)?la.com_tech:""); //업종명
				  			$('div#cluster_list ul li').eq(i).find('div.com_item label').text((i<cluster_totalHits)?la.com_cluster:""); // 제품
							$('div#cluster_list ul li').eq(i).find('div.com_tech label').text((i<cluster_totalHits)?la.inter_tech:""); //기술
				  			$('div#cluster_list ul li').eq(i).find('div.com_url label').text((i<cluster_totalHits)?la.com_item:""); // url
				  			$('div#cluster_list ul li').eq(i).find('div.mem_score label').text((i<cluster_totalHits)?la.score:""); // score
						
							$('div#loading_section').attr('hidden',true);
						}
						
						// for(var i=0;i<10;i++){
				  // 			// console.log(i+" "+list_data[i].mem_name);
				  // 			if(i>=10) break;
				  // 			//기술추천
						// 	var la = (i<interest_totalHits)?$.parseJSON(list4_data[i]):"none";
						// 	$('div#tech_list ul li').eq(i).find('div.mem_name').text((i<interest_totalHits)?la.mem_name:""); //회원명
				  // 			$('div#tech_list ul li').eq(i).find('div.com_name').text((i<interest_totalHits)?la.com_name:""); //회사명
							
				  // 			$('div#tech_list ul li').eq(i).find('div.com_cluster label').text((i<interest_totalHits)?la.com_cluster:""); //업종명
				  // 			$('div#tech_list ul li').eq(i).find('div.com_item label').text((i<interest_totalHits)?la.com_item:""); // 제품
						// 	$('div#tech_list ul li').eq(i).find('div.com_tech label').text((i<interest_totalHits)?la.com_tech:""); //기술
				  // 			$('div#tech_list ul li').eq(i).find('div.com_url label').text((i<interest_totalHits)?la.com_url:""); // url
				  // 			$('div#tech_list ul li').eq(i).find('div.mem_score label').text((i<interest_totalHits)?la.score:""); // score
						
						// 	$('div#loading_section').attr('hidden',true);
						// }
						// console.log( list_data.hobbyList.length );
				    },
				    error: function( jqXhr, textStatus, errorThrown ){
						$('div#loading_section').attr('hidden',true);
				        console.log( errorThrown );
				    }
				})
			}

			function init(){
				for(var i=0; i<4; i++){
					for(var j=0; j<10; j++){
						
						$('div#list_box div.sub_list_box').eq(i).find('ul').append('	<li> 		<div class="card_wrap"> 			<div class="card_img_box"> 				<div class="com_img"> 					<div class="card_rank"> 					</div>  				</div> 			<div class="gra_img"></div> 			<div class="mem_img"></div> 		</div> 		<div class="card_txt_box"> 			<div class="card_txt_main_box"> 				<div class="mem_name"></div> 				<div class="com_txt com_name"></div> 			<div class="mem_name2"></div> 			</div> 			<div class="card_txt_sub_box"> 		 			<div class="mem_detail com_cluster"> 				<span class="card_icon"></span> 				<label></label> 			</div> 			<div class="mem_detail com_item"> 				<span class="card_icon"></span> 				<label></label> 			</div> 			<div class="mem_detail com_tech"> 				<span class="card_icon"></span> 				<label></label> 			</div>  			<div class="mem_detail mem_score"> 				<span class="card_icon"></span> 				<label></label> 			</div> 		</div> 		</div> 		</div> 	</li>');
						
						$('div#list_box div.sub_list_box').eq(i).find('li').eq(j).find('div.card_rank').text("RANK "+(j+1)+".");
						
					
					}
					
				}
				
				
			}
		})
	</script>
	<style type="text/css">
		@charset "utf-8";
		/* Defalut Setting */
		header, footer, section, article, aside, nav, hgroup, details, menu, figure, figcaption {display:block;}
		html, body {width:100%;height:100%;margin:0 auto;padding:0;color:#363636;font-size:9pt;line-height:1.6;font-family:돋움;}
		p,h1,h2,h3,h4,h5,h6,ul,ol,li,dl,dt,dd,table,th,td,form,fieldset,legend,input,textarea,button,select,img{margin:0;padding:0;font-family:sans-serif;}
		ul, ol, li {list-style:none;}
		img, fieldset {border:0 none;vertical-align:top;}
		iframe {border:0 none;}
		table{border-collapse:collapse;}
		a:link,a:active, a:visited {text-decoration:none;cursor:pointer;}
		a:hover {text-decoration:none;cursor:pointer;}

		h1 { font-size: 1.5em }
		h2 { font-size: 1.3em }
		body {position:relative;width:100%;height:100%;-webkit-text-size-adjust:none; background: #ffffff;}

		div#top_menu { position: fixed; left:0px; top:0px; width: 100%; background: #ffffff }

		div#btn_top { position: fixed; bottom: 30px; right: 30px; width: 60px; height: 60px; background:url('icon_top_02.png') no-repeat -0px -0px; background-size: 60px 60px; display: none;  cursor:pointer; }

		div#loading_section { position: fixed; top: 0px; left: 0px; width: 100%; height: 100%; }
		div#loading_section div#opacity_box { position: relative; width: 100%; height: 100%; background: #000000; opacity: 0.7; }
		div#loading_section img { position: absolute; width: 100px; height: 100px; top:calc(50% - 50px); left:calc(50% - 50px);}
		
		div#site_title { position: relative; width: 100%; height: 20px; padding: 13px; background: #363636; color: white;font-size: 17px;}
		
		div#search_box { position: relative; width: 100%; height: 50px; padding: 20px; background: #363636; }
		div#search_box input#search_txt { position: absolute; top:20px; left:90px; width: 250px; height:50px; border: 2px solid #ffffff; background: #ffffff; padding: 0 10px; line-height: 50px; color: #666666; font-size: 18px; }
		div#search_box input#search_btn { position: absolute; top:20px; left:340px; display: inline-block; width: 50px; height: 50px; border: 2px solid #ffffff; vertical-align: middle; background: url('icon_search.png') center center no-repeat; overflow: hidden; text-indent: -9999px; cursor: pointer; margin-left: -3px; }
		div#mem_id { position: absolute; left:10px; width: 50px; margin: 5px auto; padding: -5px; width: 100%; color: white; font-size: 17px; }
		div#mem_info { position: absolute; left:400px; margin: 5px auto; padding: -5px; width: 100%; color: white; font-size: 13px; }
		
		div#list_box { position: relative; width: 100%; margin: 20px auto; min-width: 925px; max-width: 1200px; background: #e6e6e6; }

		div#list_box div.sub_list_box { position: relative; width: calc(25% - 10px);margin: 0px 5px; float: left; min-width: 220px; }
		div#list_box div.sub_list_box div.category_name { position: relative; margin: 0px auto; padding: 15px; width: calc(220px - 30px); background: #666666; color: #ffffff; }
		div#list_box div.sub_list_box ul { position: relative; margin: 5px; }
		div#list_box div.sub_list_box li { position: relative; margin: 20px auto; width: 200px;}
		div#list_box div.sub_list_box li div.card_wrap { position: relative; height: 300px; background: #FFFFFF; }
		div#list_box div.sub_list_box li div.card_wrap div.card_img_box { position: relative; height: 100px; }
		div#list_box div.sub_list_box li div.card_wrap div.card_img_box div.com_img { position: relative; height: 50px; }
		div#list_box div.sub_list_box li div.card_wrap div.card_img_box div.com_img  div.card_rank { position: relative; margin: 0px 5px; font-size: 19px; font-weight: bold; font-style: italic; color: #ffffff; }
		div#list_box div.sub_list_box li div.card_wrap div.card_img_box div.gra_img { position: relative; height: 50px; }
		div#list_box div.sub_list_box li div.card_wrap div.card_img_box div.mem_img { position: absolute; height: 50px; width: 50px; top: 25px; margin-left:calc(50% - 25px); background:url('guest.jpg') no-repeat -0px -0px; background-size: 100%; border-radius: 50px; }
		div#list_box div.sub_list_box li div.card_wrap div.card_txt_box { position: relative; }
		div#list_box div.sub_list_box li div.card_wrap div.card_txt_box div.card_txt_main_box { position: relative; text-align: center; height: 60px; margin-bottom: 10px; }
		div#list_box div.sub_list_box li div.card_wrap div.card_txt_box div.card_txt_main_box div.mem_name { position: relative; font-size: 15px; font-weight: bold; line-height: 200%; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
		div#list_box div.sub_list_box li div.card_wrap div.card_txt_box div.card_txt_main_box div.com_txt { font-size: 12px; font-weight: bold; color: #a3a3a3; line-height: 120%; }
		div#list_box div.sub_list_box li div.card_wrap div.card_txt_box div.card_txt_main_box div.com_name { position: relative; }
		div#list_box div.sub_list_box li div.card_wrap div.card_txt_box div.card_txt_main_box div.mem_name2 { position: relative; font-size: 12px; font-weight: bold; color: #a3a3a3; line-height: 120%; }
		div#list_box div.sub_list_box li div.card_wrap div.card_txt_box div.card_txt_main_box div.com_position { position: relative; }
		div#list_box div.sub_list_box li div.card_wrap div.card_txt_box div.card_txt_sub_box { position: relative; margin: 0px 10px 25px 10px; font-size: 13px; }
		div#list_box div.sub_list_box li div.card_wrap div.card_txt_box div.card_txt_sub_box div.mem_detail { position: relative; margin: 5px 0px; }
		div#list_box div.sub_list_box li div.card_wrap div.card_txt_box div.card_txt_sub_box div.mem_detail span.card_icon { display:inline-block; width: 40px; height: 20px; }
		div#list_box div.sub_list_box li div.card_wrap div.card_txt_box div.card_txt_sub_box div.mem_detail label { position: absolute; line-height: 120%; top:0px; left: 48px; right: 0px; overflow: hidden; text-overflow:ellipsis; white-space:nowrap; }
		div#list_box div.sub_list_box li div.card_wrap div.card_txt_box div.card_txt_sub_box div.com_tech span.card_icon { position: relative; background:url('icon_tech2.png') no-repeat -0px -0px; background-size: 40px 20px; }
		div#list_box div.sub_list_box li div.card_wrap div.card_txt_box div.card_txt_sub_box div.com_url span.card_icon { position: relative; background:url('icon_product.png') no-repeat -0px -0px; background-size: 40px 20px; }
		div#list_box div.sub_list_box li div.card_wrap div.card_txt_box div.card_txt_sub_box div.mem_score span.card_icon { position: relative; background:url('icon_score.png') no-repeat -0px -0px; background-size: 40px 20px; }
		div#list_box div.sub_list_box li div.card_wrap div.card_txt_box div.card_txt_sub_box div.com_item span.card_icon { position: relative; background:url('icon_keyword.png') no-repeat -0px -0px; background-size: 40px 20px; }
		div#list_box div.sub_list_box li div.card_wrap div.card_txt_box div.card_txt_sub_box div.com_cluster span.card_icon { position: relative; background:url('icon_biztype.png') no-repeat -0px -0px; background-size: 40px 20px;}
		
		div#list_box div.sub_list_box li div.card_wrap div.card_txt_box div.card_txt_sub_box div.mem_url label { cursor:pointer; }

		div#list_box div#mem_list 		ul li div.card_wrap { border: 1px solid #14b4b4; }
		div#list_box div#mem_list 		ul li div.card_wrap div.card_img_box div.com_img { background: #14b4b4; }
		div#list_box div#mem_list		ul li div.card_wrap div.card_img_box div.gra_img { position: relative; background:url('grad_a.png') no-repeat -0px -0px; background-size: 200px 50px; }
		div#list_box div#mem_list		ul li div.card_wrap div.card_txt_box div.card_txt_main_box div.com_txt { color : #14b4b4; }
		div#list_box div#item_list 		ul li div.card_wrap { border: 1px solid #00aeef; }
		div#list_box div#item_list 		ul li div.card_wrap div.card_img_box div.com_img { background: #00aeef; }
		div#list_box div#item_list		ul li div.card_wrap div.card_img_box div.gra_img { position: relative; background:url('grad_b.png') no-repeat -0px -0px; background-size: 200px 50px; }
		div#list_box div#item_list		ul li div.card_wrap div.card_txt_box div.card_txt_main_box div.com_txt { color : #00aeef; }
		div#list_box div#cluster_list 	ul li div.card_wrap { border: 1px solid #0d60b6; }
		div#list_box div#cluster_list 	ul li div.card_wrap div.card_img_box div.com_img { background: #0d60b6; }
		div#list_box div#cluster_list	ul li div.card_wrap div.card_img_box div.gra_img { position: relative; background:url('grad_c.png') no-repeat -0px -0px; background-size: 200px 50px; }
		div#list_box div#cluster_list	ul li div.card_wrap div.card_txt_box div.card_txt_main_box div.com_txt { color : #0d60b6; }
		div#list_box div#tech_list 		ul li div.card_wrap { border: 1px solid #14b4b4; }
		div#list_box div#tech_list 		ul li div.card_wrap div.card_img_box div.com_img { background: #14b4b4; }
		div#list_box div#tech_list		ul li div.card_wrap div.card_img_box div.gra_img { position: relative; background:url('grad_a.png') no-repeat -0px -0px; background-size: 200px 50px; }
		div#list_box div#tech_list		ul li div.card_wrap div.card_txt_box div.card_txt_main_box div.com_txt { color : #14b4b4; }

	</style>
</head>
<body>
	<section>
		<div id="site_title"><h2>회원수첩 - Test System</h2></div>
		<div id="search_box">
		<div id="mem_id"><h2>회원ID:</h2></div>
			<input type="text" 		id="search_txt" placeholder="Enter recommendation id"></input>
			<input type="button" 	id="search_btn" value="확인"></input>
			<div id="mem_info"><h2></h2></div>
		</div>
		
		<div id="list_box">
			<div class="sub_list_box" id="mem_list">
				<div class="category_name">
					<h1>회원기반 추천</h1><div class="mem_add_info"></div>
				</div>
				<ul></ul>
			</div>
			<div class="sub_list_box" id="item_list">
				<div class="category_name">
					<h1>융합기반 추천</h1><div class="cluster_add_info"></div>
				</div>
				<ul></ul>
			</div>
			<div class="sub_list_box" id="cluster_list">
				<div class="category_name">
					<h1>기술기반 추천</h1><div class="item_add_info"></div>
				</div>
				<ul></ul>
			</div>
			<!-- <div class="sub_list_box" id="tech_list">
				<div class="category_name">
					<h1>기술기반 추천</h1><div class="tech_add_info"></div>
				</div>
				<ul></ul>
			</div> -->
		</div>
	</section>
	<div id="top_menu" hidden>
		<div id="list_box">
			<div class="sub_list_box" id="mem_list">
				<div class="category_name">
					<h1>회원기반 추천</h1><div class="mem_add_info"></div>
				</div>
			</div>
			<div class="sub_list_box" id="item_list">
				<div class="category_name">
					<h1>융합기반 추천</h1><div class="item_add_info"></div>
				</div>
			</div>
			<div class="sub_list_box" id="cluster_list">
				<div class="category_name">
					<h1>기술기반 추천</h1><div class="cluster_add_info"></div>
				</div>
			</div>
			<!-- <div class="sub_list_box" id="tech_list">
				<div class="category_name">
					<h1>기술기반 추천</h1><div class="tech_add_info"></div>
				</div>
			</div> -->
		</div>
	</div>
	<div id="btn_top"></div>
	<div id="loading_section" hidden ><div id="opacity_box"></div><img src="ajax_loading.gif"/></div>
</body>
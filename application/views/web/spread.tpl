<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>91特贷网</title>
    <meta name="viewport" content="initial-scale=1, maximum-scale=1">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <style>
      html{font-size: 20px}
      .page_header{background: url(/static/web/spread/top.jpg) no-repeat;display: flex;height:3rem; background-size: 100%;}
      .page_header_icon{flex: 0 0 100px;text-align: center;}
      .page_header_icon > i{font-size: 3rem}
      .head-name{padding:10px 0 0 0;margin:0;font-size: 0.8rem;}
      .page_header_content{}
      .page_header_content_text_wraper{display: flex;}
      .about{
        text-align: center;border-radius: 0.8rem;width: 7rem;height: 1.5rem;border: 1px solid #1681c0;
        line-height: 1.5rem;color:  #1681c0;margin-right: 10px;font-size: .65rem
       }
       .header_content_text{
         font-size: 0.67rem;padding: 3px 10px 0px 0px
       }
       .flex-wrapper{display: flex;justify-content: space-between;font-size: 0.6rem;padding-bottom: 1rem}
       .fensi{color:#1681c0 ;padding-right: 1rem}
       .page_list{background: url(/static/web/spread/WechatIMG396.jpeg) no-repeat;height: 9rem;background-size: 100% 100%}
       .blueColor{color:#1681c0;padding-left: .2rem }

       .page-list-item{
        background: #fff;
       }
        .flex-item{display: flex;padding-top: .3rem}
      .cell{flex:1;}
      .cell img{width: 100%}
      .text-cen{text-align: center;padding: 1rem;font-size: 1rem}
      .search-input{width: 95%;margin:1rem auto;}
      input::-webkit-input-placeholder { color: #ccc}
      .yuan{
       height: 2rem;border: 1px solid #ccc;
        line-height: 2rem;
        text-align: center;
        color: #000;
      }

    </style>
    <link rel="stylesheet" href="https://g.alicdn.com/msui/sm/0.6.2/css/sm.min.css">
    <link rel="stylesheet" href="https://g.alicdn.com/msui/sm/0.6.2/css/sm-extend.min.css">
    <link rel="stylesheet" type="text/css" href="https://at.alicdn.com/t/font_45320_txzo78zwlv1gu8fr.css">
    <script type="text/javascript">
    (function(w,d,t,s,q,m,n){if(w.utq)return;q=w.utq=function(){q.process?q.process(arguments):q.queue.push(arguments);};q.queue=[];m=d.getElementsByTagName(t)[0];n=d.createElement(t);n.src=s;n.async=true;m.parentNode.insertBefore(n,m);})(window,document,'script','https://image.uc.cn/s/uae/g/0s/ad/utracking.js');utq('set', 'convertMode', true);utq('set', 'trackurl', 'huichuan.sm.cn/lp');
    </script>
    <script type="text/javascript">
      (function(root) {
            window.logsMomoUserId="180502165014766";
            var jsonpScript=document.createElement("script");
            jsonpScript.type="text/javascript";
            jsonpScript.src="https://s.momocdn.com/w/u/others/custom/conver/mycustom/converjsonp.js?r="+Math.ceil(Math.random()*10);
            var heads = document.getElementsByTagName('script')[0];
            heads.parentNode.insertBefore(jsonpScript, heads);
      })(window);
    </script>
  </head>
  <body>
    <div class="page-group">
      <div class="page page-current">
        <div class="content">
          <div class="page_header">

          </div>
          <div class="page_list">

          </div>
          <div class="page-list-item mt-5">

          </div>
          <!--  -->
          <div class="page-list-item ">
            <div class="flex-item">
              <div class="cell cell-1">
                <img src="/static/web/spread/1.jpg" alt="">
              </div>
              <div class="cell cell-2">
                <img src="/static/web/spread/2.jpg" alt="">
              </div>
              <div class="cell cell-3">
                <img src="/static/web/spread/3.jpg" alt="">
              </div>
            </div>
          </div>
          <div class="text-cen">贷款申请</div>
          <div class="page-list-item ">
          <form action="/web/spread" method="post">
            <input type="hidden" name="channel" value="{{$channel}}">
            <div style="display: flex;padding-top: .3rem">
              <div style="width: 60%;margin-left: 0.5rem;border: 1px solid #b4b4b4;border-radius: .25rem;">
                <input type="text" name="name" style="border:0;height: 2rem;padding-left: .4rem;color: #3d4145" placeholder='请填写您的真实姓名  '/>
              </div>
              <div style="padding-top: 0.5rem;padding-left: .3rem;padding-right: .3rem">
                <input type="radio" name="sex"  checked="checked" value="1" />
                <span>先生</span>
              </div>
              <div style="padding-top: 0.5rem;">
                <input type="radio" name="sex" value="2" />
                <span>女士</span>
              </div>
            </div >
              <div class="search-input col-85">
                <input style="height: 2rem;font-size: .9rem" type="search" name="mobile" placeholder='请填写您的真实手机号码'/>
              </div>
              <div class="search-input " style="width:97%;display: flex; margin: 1rem 0 0 0.5rem;">
                <input style="height: 2rem;font-size: .9rem" type="number" name="daiMoney" placeholder='请填写贷款金额'/>
                <div style=" width: 17%;">
                 <div class="yuan">万元 </div>
                </div>
              </div>
              <div style="display: flex;padding-top: 1rem">
                <div class="cell cell-3" style="padding-left: 1rem">
                  <div style="width: 7rem;height:1.8rem;border: 1px solid #ccc; line-height: 1.8rem;padding-left: 2.3rem">城市</div>
                </div>
                 <div class="cell cell-3">
                   <select name="city" style="width: 90%;height: 1.8rem;padding-left: 2.6rem;">
                     <option value="深圳市">深圳</option>
                     <option value="上海市">上海</option>
                   </select>
                 </div>
              </div>
               <p style="padding: 0 .5rem"><a class="button button-fill" id="submit" style="height: 2.25rem;line-height: 2.25rem;font-size: 1rem">提交</a></p>
               <div style="display: flex;font-size: .5rem;padding-left: .5rem">
                  <input type="checkbox" id="isRead"  checked="checked"/>
                  <span>本人已阅读并同意</span>
                  <span style="color: #0894ec" data-popup=".popup-about" class="open-popup">《特贷网平台服务协议》</span>
               </div>
               <p style="font-size: 12px;margin: 1em;">投资有风险，投资需谨慎；审批额度、到账时间视个人情况而定贷款资金由银行等持牌放贷机构提供。</p>
             </form>
             <!-- <div class="text-cen">提醒</div> -->
             <!-- <div class="text-cen">合作平台</div> -->
<!--              <div style="display: flex;">
               <div class="cell cell-3">
                 <img src="/static/web/spread/ping.jpg" alt="">
               </div>
               <div class="cell cell-3">
                 <img src="/static/web/spread/ren.jpg" alt="">
               </div>
               <div class="cell cell-3">
                 <img src="/static/web/spread/yi.jpg" alt="">
               </div>
               <div class="cell cell-3">
                 <img src="/static/web/spread/heng.jpg" alt="">
               </div>
               <div class="cell cell-3">
                 <img src="/static/web/spread/you.jpg" alt="">
               </div>
             </div> -->
             <style type="text/css">
                .tip{margin: 0em;font-size: 16px}
                .tip p{margin: 0.5em 10px !important; text-align: center;}
             </style>
             <div class="tip">
              <!-- <p>杜绝借款犯罪</p><p>倡导合法借贷</p><p>信守借贷合约</p>深圳享帮帮科技有限公司 粤ICP备18044371号-1 -->
              <p style="text-align: right;font-size: 12px">深圳享帮帮科技有限公司&nbsp;粤ICP备18044371号-1</p>
             </div>
          </div>
          <!--  -->
        </div>

      </div>
      <!--  -->
<div class="popup popup-about" style="background: #fff;">
  <div class="content-block">
    <p style="text-align: center;">特贷网用户在线申请协议</p>
    <div style="font-size: .7rem">
      特贷网站（91tedai.com及其二级域名）由深圳享帮帮投资咨询有限公司运营。我们依照以下协议相关服务。请您使用特贷网服务前仔细阅读本协议。 您只有完全同意所有协议，才能成为特贷网的用户并使用相应服务。您在注册为特贷网用户过程中点击“立即申请”、“立即注册”或“注册”按钮即表示您已仔细阅读并明确同意遵守本注册协议以及经参引而并入其中的所有条款、政策以及指南，并受该等规则的约束（合称"本注册协议"）。我们可能根据法律法规的要求或业务运营的需要，对本注册协议不时进行修改。除非另有规定，否则任何变更或修改将在修订内容于特贷网发布之时立即生效，您对特贷网的使用、继续使用将表明您接受此等变更或修改。如果您不同意本注册协议（包括我们可能不定时对其或其中引述的其他规则所进行的任何修改）的全部规定，否则请勿使用特贷网提供的所有服务，或您可以主动取消特贷网提供的服务。 为了便于您了解适用于您使用特贷网的条款和条件，我们将在特贷网上发布我们对本协议的修改，您应不时地审阅本协议以及经参引而并入其中的其他规则。
    </div>
    <div>
      <p>一、服务内容</p>
      <div>
        1.1特贷网的具体服务内容由我们根据实际情况提供，包括但不限于信息、图片、文章、评论、积分抽奖活动等，我们将定期或不定期根据用户的意愿以电子邮件、短信、电话的方式为用户提供活动信息，并向用户提供学习、交流平台（以上统称“服务”）。我们对提供的服务拥有最终解释权。
      </div>
      <div>
        1.2特贷网服务仅供个人用户使用。除我们书面同意，您或其他用户均不得将特贷网上的任何信息用于商业目的。
      </div>
      <div>
        1.3您使用特贷网服务时所需的相关的设备以及网络资源等（如个人电脑及其他与接入互联网或移动网有关的装置）及所需的费用（如为接入互联网而支付的电话费及上网费）均由您自行负担。
    </div>
    <p>二、信息提供和隐私保护</p>
      <div>
        2.1 您在访问、使用特贷网或申请使用特贷网服务时，必须提供本人真实的个人信息，且您应该根据实际变动情况及时更新个人信息。最终及保护用户隐私是我们的重点原则，我们通过各种技术手段和强化内部管理等办法提供隐私保护服务功能，充分保护您的个人信息安全。
      </div>
      <div>
        2.2 特贷网不负责审核您提供的个人信息的真实性、准确性或完整性，因信息不真实、不准确或不完整而引起的任何问题及其后果，由您自行承担，且您应保证我们免受由此而产生的任何损害或责任。若我们发现您提供的个人信息是虚假、不准确或不完整的，我们有权自行决定终止向您提供服务。
      </div>
      <div>
        2.3 您已明确授权，为提供服务、履行协议、解决争议、保障交易安全等目的，我们对您提供的、我们自行收集的及通过第三方收集的您的个人信息、您申请服务时的相关信息、您在使用服务时储存在特贷网的非公开内容以及您的其他个人资料（以下简称“个人资料”）享有留存、整理加工、使用和披露的权利，具体方式包括但不限于：</br>
          （1）出于为您提供服务的需要在本网站公示您的个人资料；</br>
          （2）由人工或自动程序对您的个人资料进行获取、评估、整理、存储；</br>
          （3）使用您的个人资料以改进本网站的设计和推广；</br>
        （4）使用您提供的联系方式与您联络并向您传递有关服务和管理方面的信息；</br>
          （5）对您的个人资料进行分析整合，并向为您提供服务的金融机构提供为完成该项服务必要的信息。</br>
          （6）对于特贷网未设立物理营业网点的城市，您的个人资料将推荐至该城市第三方贷款服务公司，以便更好的解决您的实际问题，该种情况我们将以短信的形式告知您。</br>
          （7）在您违反与我们或我们的其他用户签订的协议时，披露您的个人资料及违约事实，将您的违约信息写入黑名单并与必要的第三方共享数据，以供我们及第三方审核、追索之用。</br>
          （8）其他必要的使用及披露您个人资料的情形。您已明确同意本条款不因您终止使用特贷网服务而失效。如因我们行使本条款项下权利使您遭受损失，我们对该等损失免责。</br>
      </div>
      <div>
        2.4 为更好地为您提供服务，您同意并授权特贷网可与其合作的第三方进行联合研究，并可将通过本协议获得的您的信息投入到该等联合研究中。但特贷网与其合作的第三方在开展上述联合研究前，应要求其合作的第三方对在联合研究中所获取的您的信息予以保密。
      </div>
      <div>
        2.5 我们不会向与您无关的第三方恶意泄露或免费提供您的个人资料，但下列情况除外： </br>
        （1）事先获得您的明确授权；</br>
（2）按照相关司法机构或政府主管部门的要求；</br>
（3）客户自行向除特贷网以外的机构公开其个人隐私信息；</br>
（4）不可抗力：任何由于黑客攻击、电脑病毒侵入、自然灾害等其他不可抗力事件导致用户个人隐私信息的泄露。</br>
（5）以维护我们合法权益之目的；</br> 
（6）维护社会公众利益;</br>
（7）为了确保特贷网业务和系统的完整与操作。</br>
（8）符合其他合法要求。<br>
      </div>
 <p>三、使用准则</p>
<div>
  3.1 您在使用特贷网服务过程中，必须遵循国家的相关法律法规，不通过特贷网发布、复制、上传、散播、分发、存储、创建或以其它方式公开含有以下内容的信息：<br>
（1）反对宪法所确定的基本原则的；<br>
（2）危害国家安全，泄露国家秘密，颠覆国家政权，破坏国家统一的；<br>
（3）损害国家荣誉和利益的； <br>
（4）煽动民族仇恨、民族歧视，破坏民族团结的；<br>
（5）破坏国家宗教政策，宣扬邪教和封建迷信的；<br>
（6）散布谣言，扰乱社会秩序，破坏社会稳定的；<br>
（7）散布淫秽、色情、赌博、暴力、凶杀、恐怖或者教唆犯罪的、欺诈性的或以其它令人反感的讯息、数据、信息、文本、音乐、声音、照片、图形、代码或其它材料； <br>
（8）侮辱或者诽谤他人，侵害他人合法权益的； <br>
（9）其他违反宪法和法律、行政法规或规章制度的； <br>
（10）可能侵犯他人的专利、商标、商业秘密、版权或其它知识产权或专有权利的内容；<br>
（11）假冒任何人或实体或以其它方式歪曲您与任何人或实体之关联性的内容；<br>
（12）未经请求而擅自提供的促销信息、政治活动、广告或意见征集；<br>
（13）任何第三方的私人信息，包括但不限于地址、电话号码、电子邮件地址、身份证号以及信用卡卡号；<br>
（14）病毒、不可靠数据或其它有害的、破坏性的或危害性的文件；<br>
（15）与内容所在的互动区域的话题不相关的内容； <br>
（16）依我们的自行判断，足以令人反感的内容，或者限制或妨碍他人使用或享受互动区域或特贷网的内容，或者可能使我们或我们关联方或其他用户遭致任何类型损害或责任的内容；<br> 
（17）包含法律或行政法规禁止内容的其他内容。</p>
</div>
<div>
  3.2 用户不得利用特贷网的服务从事下列危害互联网信息网络安全的活动：<br>
（1）未经允许，进入计算机信息网络或者使用计算机信息网络资源；<br>
（2）未经允许，对计算机信息网络功能进行删除、修改或者增加；<br>
（3）未经允许，对进入计算机信息网络中存储、处理或者传输的数据和应用程序进行删除、修改或者增加；<br>
（4）故意制作、传播计算机病毒等破坏性程序； <br>
（5）其他危害计算机信息网络安全的行为。
</div>

<div>
  3.3 我们保留在任何时候为任何理由而不经通知地过滤、移除、筛查或编辑本网站上发布或存储的任何内容的权利，您须自行负责备份和替换在本网站发布或存储的任何内容，成本和费用自理。</div>
<div>3.4 您须对自己在使用特贷网服务过程中的行为承担法律责任。若您为限制行为能力或无行为能力者，则您的法定监护人应承担相应的法律责任。</div>
<div>3.5 如您的操作影响系统总体稳定性或完整性，我们将暂停或终止您的操作，直到相关问题得到解决。
</div>



<p>四、免责声明</p>
<div>
  4.1 特贷网是一个开放平台，用户将文章或照片等个人资料上传到互联网上，有可能会被其他组织或个人复制、转载、擅改或做其它非法用途，用户必须充分意识此类风险的存在。作为网络服务的提供者，我们对用户在任何论坛、个人主页或其它互动区域提供的任何陈述、声明或内容均不承担责任。您明确同意使用特贷网服务所存在的风险或产生的一切后果将完全由您自身承担，我们对上述风险或后果不承担任何责任。
</div>
<div>
  4.2 您违反本注册协议、违反道德或法律的，侵犯他人权利（包括但不限于知识产权）的，我们不承担任何责任。同时，我们对任何第三方通过特贷网发送服务或包含在服务中的任何内容不承担责任。
</div>
<div>
4.3 对您、其他用户或任何第三方发布、存储或上传的任何内容或由该等内容导致的任何损失或损害，我们不承担责任。
</div>
<div>
  4.4 对任何第三方通过特贷网可能对您造成的任何错误、中伤、诽谤、诬蔑、不作为、谬误、淫秽、色情或亵渎，我们不承担责任
</div>
<div>
  4.5 对黑客行为、计算机病毒、或因您保管疏忽致使帐号、密码被他人非法使用、盗用、篡改的或丢失，或由于与本网站链接的其它网站所造成您个人资料的泄露，我们不承担责任。如您发现任何非法使用用户帐号或安全漏洞的情况，请立即与我们联系。
</div>
<div>
  4.6 因任何非特贷网原因造成的网络服务中断或其他缺陷，我们不承担任何责任。
</div>
<div>
  4.7 我们不保证服务一定能满足您的要求；不保证服务不会中断，也不保证服务的及时性、安全性、准确性。
</div>
<div>
  4.8 任何情况下，因使用特贷网而引起或与使用特贷网有关的而产生的由我们负担的责任总额，无论是基于合同、保证、侵权、产品责任、严格责任或其它理论，均不得超过您因访问或使用本网站而向特贷网支付的任何报酬（如果有的话）。
</div>
<div>
  4.9 特贷网提供免费的贷款搜索和推荐服务，贷款过程中遇到的任何预先收费均为诈骗行为，请保持警惕避免损失。
</div>
<p>五、服务变更、中断或终止</p>
<div>
  5.1 如因升级的需要而需暂停网络服务、或调整服务内容，我们将尽可能在网站上进行通告。由于用户未能及时浏览通告而造成的损失，我们不承担任何责任。
</div>
<div>
  5.2 您明确同意，我们保留根据实际情况随时调整特贷网提供的服务内容、种类和形式，或自行决定授权第三方向您提供原本我们提供的服务。因业务调整给您或其他用户造成的损失，我们不承担任何责任。同时，我们保留随时变更、中断或终止特贷网全部或部分服务的权利。
</div>
<div>
  5.3 发生下列任何一种情形，我们有权单方面中断或终止向您提供服务而无需通知您，且无需对您或第三方承担任何责任： （1）您提供的个人资料不真实； （2）您违反本服务条款； （3）未经我们书面同意，将特贷网平台用于商业目的。
</div>
<div>
  5.4 您可随时通知我们终止向您提供服务或直接取消特贷网服务。自您终止或取消服务之日起，我们不再向您承担任何形式的责任。
</div>
<p>六、知识产权及其它权利</p>
<div>
  6.1 用户可以充分利用特贷网平台共享信息。您可以在特贷网发布从特贷网个人主页或其他网站复制的图片和信息等内容，但这些内容必须属于公共领域或者您拥有以上述使用方式使用该等内容的权利，且您有权对该等内容作出本条款下之授权、同意、认可或承诺。
</div>
<div>
  6.2 对您在特贷网发布或以其它方式传播的内容，您作如下声明和保证：<br>
（1）对于该等内容，您具有所有权或使用权；<br>
（2）该等内容是合法的、真实的、准确的、非误导性的； <br>
（3）使用和发布此等内容或以其它方式传播此等内容不违反本服务条款，也不侵犯任何人或实体的任何权利或造成对任何人或实体的伤害。
</div>
<div>
  6.3 未经相关内容权利人的事先书面同意，您不得擅自复制、传播在特贷网的该等内容，或将其用于任何商业目的，所有这些资料或资料的任何部分仅可作为个人或非商业用途而保存在某台计算机内。否则，我们及/或权利人将追究您的法律责任。
</div>
<div>
  6.4 您在特贷网发布或传播的自有内容或具有使用权的内容，您特此同意如下： <br>
（1）授予我们使用、复制、修改、改编、翻译、传播、发表此等内容，从此等内容创建派生作品，以及在全世界范围内通过任何媒介（现在已知的或今后发明的）公开展示和表演此等内容的权利； <br>
（2）授予特贷网及其关联方和再许可人一项权利，可依他们的选择而使用用户有关此等内容而提交的名称；<br> 
（3）授予我们在第三方侵犯您在特贷网的权益、或您发布在特贷网的内容情况下，依法追究其责任的权利（但这并非我们的义务）；
</div>
<div>
  6.5 您在特贷网公开发布或传播的内容、图片等为非保密信息，我们没有义务将此等信息作为您的保密信息对待。在不限制前述规定的前提下，我们保留以适当的方式使用内容的权利，包括但不限于删除、编辑、更改、不予采纳或拒绝发布。我们无义务就您提交的内容而向您付款。一旦内容已在特贷网发布，我们也不保证向您提供对在特贷网发布内容进行编辑、删除或作其它修改的机会。
</div>
<div>
  6.6 如有权利人发现您在特贷网发表的内容侵犯其权利，并依相关法律、行政法规的规定向我们发出书面通知的，特贷网有权在不事先通知您的情况下自行移除相关内容，并依法保留相关数据。您同意不因该种移除行为向我们主张任何赔偿，如我们因此遭受任何损失，您应向赔偿我们的损失（包括但不限于赔偿各种费用及律师费）。
</div>
<div>
  6.7 若您认为您发布第6.6条指向内容并未侵犯其他方的权利，您可以向我们以书面方式说明被移除内容不侵犯其他方权利的书面通知，该书面通知应包含如下内容：您详细的身份证明、住址、联系方式、您认为被移除内容不侵犯其他方权利的证明、被移除内容在特贷网上的位置以及书面通知内容的真实性声明。我们收到该书面通知后，有权决定是否恢复被移除内容。
</div>
<div>
  6.8 您特此同意，如果6.7条中的书面通知的陈述失实，您将承担由此造成的全部法律责任，如我们因此遭受任何损失，您应向赔偿我们的损失（包括但不限于赔偿各种费用及律师费）
</div>
<p>七、特别约定</p>
<div>
  7.1 您使用本服务的行为若有任何违反国家法律法规或侵犯任何第三方的合法权益的情形时，我们有权直接删除该等违反规定之信息，并可以暂停或终止向您提供服务。<br>
7.2 若您利用特贷网服务从事任何违法或侵权行为，由您自行承担全部责任，因此给我们或任何第三方造成任何损失，您应负责全额赔偿，并使我们免受由此产生的任何损害。<br>
7.3 您同意我们通过重要页面的公告、通告、电子邮件以及常规信件的形式向您传送与特贷网服务有关的任何通知和通告。<br>
7.4 如您有任何有关与特贷网服务的个人信息保护相关投诉，请您与我们联系，我们将在接到投诉之日起15日内进行答复。<br>
7.5 本服务条款之效力、解释、执行均适用中华人民共和国法律。<br>
7.6 如就本协议内容或其执行发生任何争议，应尽量友好协商解决；协商不成时，任何一方均可向特贷网所在地的人民法院提起诉讼。<br>
7.7 本服务条款中的标题仅为方便而设，不影响对于条款本身的解释。本服务条款最终解释权归深圳享帮帮投资咨询有限公司所有。
</div>

    </div>
    <p style="text-align: center;"><a href="#" class="close-popup">关闭</a></p>
    <p></p>
  </div>
</div>
        <!--  -->
    </div>
    <script type='text/javascript' src='https://g.alicdn.com/sj/lib/zepto/zepto.min.js' charset='utf-8'></script>
    <script type='text/javascript' src='https://g.alicdn.com/msui/sm/0.6.2/js/sm.min.js' charset='utf-8'></script>
    <script type='text/javascript' src='https://g.alicdn.com/msui/sm/0.6.2/js/sm-extend.min.js' charset='utf-8'></script>

  </body>
</html>
<script>
$.init()
$(function() {
  $(document).on('click','.popup-about', function () {
      console.log('About Popup opened')
  });
  $(document).on('click','#submit', function () {
    var name     = $('input[name=name]').val();
    var mobile   = $('input[name=mobile]').val();
    var daiMoney = $('input[name=daiMoney]').val();
    if (!name) {
      alert('请填写您的姓名')
      return
    }
    if (!mobile) {
      alert('请填写您的联系方式')
      return
    }
    if (!daiMoney) {
      alert('请填写您的要贷款的金额')
      return
    }
    if (!$("#isRead").is(":checked")) {
      alert('请先阅读用户协议！')
      return
    }
    $.post('/web/spread/submit', $("form").serialize(), function(res){
      console.log(res);
      if (res.errcode == 300) {
        alert(res.errmsg)
        return
      }
      //UC
      utq('track', 'FormSubmit', '55580');
      //momo
      window.sendLosMeth&& window.sendLosMeth("submit");
      name     = $('input[name=name]').val('');
      mobile   = $('input[name=mobile]').val('');
      daiMoney = $('input[name=daiMoney]').val('');
      alert(res.errmsg)
    })
  });
});
</script>



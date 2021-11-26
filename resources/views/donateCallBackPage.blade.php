<div style="text-align: center; margin-top: 15%">
@if($Status === 'SUCCESS')
<div style="margin: 0 auto;">
<h1>捐款成功</h1>
<h1>請到電子郵件信箱確認收據</h1>
</div>
@endif

@if($Status !== 'SUCCESS' )
<div style="margin: 0 auto;">
<h1>捐款失敗</h1>
<h1>{{$Message}}</h1>
</div>
@endif

</div>
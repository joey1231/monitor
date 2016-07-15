
<!DOCTYPE html>
<html lang="en" class="body-full-height">
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<head>        
        <!-- META SECTION -->
        <title>DWE Biz Platform Prototype 1</title>           
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="robots" content="noindex,nofollow"/>
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />        
        
        <!-- CSS INCLUDE -->        
        <link rel="stylesheet" type="text/css" id="theme" href="{{ url('') }}/media/css/theme-default.css"/>
        <!-- EOF CSS INCLUDE -->     
        
        <script type="text/javascript" src="https://code.jquery.com/jquery-2.2.0.min.js" ></script>
    </head>
    <body>
        
        <div class="login-container">
        
            <div class="login-box animated fadeInDown">
                <div class="login-logo-text"><h4 style="color: #fff;">DWE Biz Platform Prototype 1</h4></div>
                <div class="login-body">
                    <div class="login-title"><strong>Welcome</strong>, Please login</div>
                    <form action="{{ url('login') }}" method="post" class="form-horizontal">
                        @if(count($errors->all())>0)
                        <div class="alert alert-danger">
                             @foreach($errors->all() as $err)
                                {{$err}}<br/>
                             @endforeach 
                        </div>
                        @endif
                        
                        {{ csrf_field() }}

                    <div class="form-group">
                        <div class="col-md-12">                            
                                 <input id="identity" name="email" required type="text" placeholder="Username" class="form-control" />
                         </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12">
                             <input id="password" name="password" required type="password" placeholder="Password" class="form-control" />
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-6">
                           
                        </div>
                        <div class="col-md-6">
                            <button name="btnLogin" class="btn btn-info btn-block">Log In</button>
                        </div>
                    </div>                        
                    </form>
                </div>
                <div class="login-footer">
                    <div class="pull-left">
                        &copy; 2016 DWE BizPlatform | <a href="http://www.dreamwareenterprise.com">DreamWare Enterprise</a>
                    </div>
                </div>
            </div>
            
        </div>
    
  
        
    </body>

</html>
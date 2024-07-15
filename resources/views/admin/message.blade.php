@if  (Session::has('error'))
        
    <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4><i class="icon fa fa-ban"></i> Error!</h4> {{Session::has('error')}}
   
    </div>
    
@endif

@if  (Session::has('Sucsess'))
    <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4><i class="icon fa fa-check"></i> Sucsess!</h4> {{Session::has('Sucsess')}}
   
    </div>
@endif
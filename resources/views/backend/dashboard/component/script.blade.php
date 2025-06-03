
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/plugins/metisMenu/jquery.metisMenu.js') }}"></script>
    <script src="{{ asset('js/plugins/slimscroll/jquery.slimscroll.min.js') }}"></script>
    <script src="{{ asset('library/library.js') }}"></script>


    <!-- jQuery UI -->

    @if(isset($config['js']) && is_array($config['js']))
        @foreach($config['js'] as $key => $val)
            {!! '<script src = "'.$val.'"></script>'!!}
        @endforeach
    @endif
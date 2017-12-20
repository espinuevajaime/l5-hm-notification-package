<notification-container
        @if(!empty($class))
        class="{{ $class }}"
        @endif
        name="{{ $name }}"
        stack="{{ $stack ?? config('system-notification.stack') }}"
        :config="{{ $config ?? config('system-notification.config') }}"
        @if(Session::has("notify.$name"))
        :notification='{!! SystemNotification::extract($name) !!}'
        @endif>
    @isset($slot){!! $slot !!}@endisset
</notification-container>

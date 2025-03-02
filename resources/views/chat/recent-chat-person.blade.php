@foreach($users as $user)
    @component('components.recent-chat')
        @slot('id')
            {{$user->id}}
        @endslot
        @slot('name')
            {{$user->name}}
        @endslot

        @slot('email')
            {{$user->email}}
        @endslot
    @endcomponent
@endforeach


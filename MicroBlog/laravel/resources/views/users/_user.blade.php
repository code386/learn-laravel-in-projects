<div class="list-group-item">
  <img class="mr-3" src="{{ $user->gravatar(30) }}" alt="{{ $user->name }}">
  <a href="{{ route('users.show', $user) }}">
    {{ $user->name }}
  </a>
  @can('destroy', $user)
    <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="float-right">
      @csrf
      @method('DELETE')
      <button type="submit" class="btn btn-sm btn-danger delete-btn">删除</button>
    </form>
  @endcan
</div>

<h1>Welcome to Admin Dashboard</h1>
<form action="{{ route('admin.logout') }}" method="POST">
    @csrf
    <button type="submit">Logout</button>
</form>

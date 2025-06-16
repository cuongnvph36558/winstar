<h2>Add Banner</h2>
<form method="POST" action="{{ route('banners.store') }}" enctype="multipart/form-data">
    @csrf
    <input type="text" name="title" placeholder="Title"><br>
    <input type="text" name="link" placeholder="Link (optional)"><br>
    <input type="file" name="image"><br>
    <select name="status">
        <option value="active">Active</option>
        <option value="inactive">Inactive</option>
    </select><br>
    <input type="number" name="position" placeholder="Position"><br>
    <button type="submit">Save</button>
</form>
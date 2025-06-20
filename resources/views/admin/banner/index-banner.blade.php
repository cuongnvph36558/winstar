<h2>Banner List</h2>
<a href="{{ route('banners.create') }}">Add Banner</a>

@foreach($banners as $banner)
    <div>
        <h3>{{ $banner->title }}</h3>
        <img src="{{ asset('storage/' . $banner->image) }}" width="200">
        <p>Link: {{ $banner->link }}</p>
        <p>Status: {{ $banner->status }}</p>
        <a href="{{ route('banners.edit', $banner->id) }}">Edit</a>
        <form action="{{ route('banners.destroy', $banner->id) }}" method="POST" style="display:inline;">
            @csrf
            @method('DELETE')
            <button type="submit">Delete</button>
        </form>
    </div>
@endforeach
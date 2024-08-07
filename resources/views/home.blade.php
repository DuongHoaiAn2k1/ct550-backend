<h1>Xin chao cac ban</h1>
<h2>Toi ten la: {{$name}}</h2>
<hr>

 {{-- @for ($i = 0; $i < 9; $i++) <p>Phan tu thu {{$i+1}}</p>
@endfor 
 @while ($number <= 6) <p>Phan tu thu {{$number+ 1}}</p>
@php
$number++
@endphp
@endwhile 
<p>Danh sach sinh vien</p>

 @foreach ($users as $key => $item )
<p>{{$key+1}} - {{$item}}</p>
@endforeach  --}}

@forelse ($users as $key => $item )
<p>{{$key+1}} - {{$item}}</p>
@empty
<p>Danh sach rong</p>
@endforelse
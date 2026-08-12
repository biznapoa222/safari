@extends('layouts.admin')
@section('title', $definition['label'])
@section('content')
<div class="page-heading"><div><p class="eyebrow">Website CMS</p><h1>{{ $definition['label'] }}</h1><p>Edit text and replace images without changing the website layout.</p></div><a href="{{ route('home') }}" target="_blank" class="button button-secondary">View website</a></div>
@include('admin.partials.flash')
<form method="POST" action="{{ route('admin.cms.content.update', $section) }}" enctype="multipart/form-data" class="ops-panel">
 @csrf @method('PUT')
 <div class="ops-form-grid website-settings-grid">
 @foreach($definition['fields'] as $key => $field)
  @php $value=old("content.$key", $values[$key] ?? $field['default'] ?? ''); @endphp
  <label class="{{ $field['type']==='textarea' ? 'span-2' : '' }}">{{ $field['label'] }}
   @if($field['type']==='textarea')<textarea name="content[{{ $key }}]" rows="4">{{ $value }}</textarea>
   @elseif($field['type']==='image')
    <input name="content[{{ $key }}]" value="{{ $value }}" placeholder="Image URL or stored path">
    <input type="file" name="uploads[{{ $key }}]" accept="image/jpeg,image/png,image/webp,image/gif">
    @if($value)<span style="display:flex;gap:12px;align-items:center;margin-top:8px"><img src="{{ \App\Support\MediaPath::publicUrl($value) }}" alt="" style="width:120px;height:72px;object-fit:cover;border-radius:6px"><small>JPG, PNG, WebP or GIF; max 8 MB.<br><input type="checkbox" name="remove[{{ $key }}]" value="1" style="width:auto"> Remove current image</small></span>@endif
   @else<input name="content[{{ $key }}]" value="{{ $value }}">@endif
  </label>
 @endforeach
 </div>
 @if($errors->any())<div class="ops-alert ops-alert--error" style="margin:18px">{{ $errors->first() }}</div>@endif
 <div class="ops-form-footer"><a href="{{ route('admin.cms.index') }}" class="button button-secondary">Back</a><button class="button button-primary">Save changes</button></div>
</form>
@endsection

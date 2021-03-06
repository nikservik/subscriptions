@extends('admin.layout')

@section('content')
<h1 class="page-header">
	<a href="/users" class="text-white">@lang('subscriptions::admin/users.listTitle')</a> 
</h1>
<h2 class="sub-header">{{ $user->name }}</h2>
<div class="mb-4 text-right">
    <a class="button small mr-4" href="/users/{{ $user->id }}/edit">
        @lang('subscriptions::admin/users.modify')
    </a>
    <a class="button small red" href="javascript:document.user_delete.submit()" onclick="return confirm('@lang('subscriptions::admin/users.confirmDelete')')">
        @lang('subscriptions::admin/users.delete')
    </a>
</div>
    <form name="user_delete" action="/users/{{ $user->id }}" method="POST">
        @csrf 
        @method('DELETE')
    </form>

<div class="flex items-center mt-8 mb-4 mx-10">
    @if($user->hasVerifiedEmail())
        ✅
    @else
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20"class="inline-block fill-current text-gray-500"><path class="heroicon-ui" d="M12 22a10 10 0 1 1 0-20 10 10 0 0 1 0 20zm0-2a8 8 0 1 0 0-16 8 8 0 0 0 0 16zm1-8.41l2.54 2.53a1 1 0 0 1-1.42 1.42L11.3 12.7A1 1 0 0 1 11 12V8a1 1 0 0 1 2 0v3.59z"/></svg>
    @endif
    <div class="px-2">{{ $user->email }}</div>
    @if (! $user->hasVerifiedEmail())
        <div><a class="button small light" href="javascript:document.user_verify.submit()">@lang('subscriptions::admin/users.verify')</a></div>
        <form name="user_verify" action="/users/{{ $user->id }}/verify" method="POST">
            @csrf 
            @method('PATCH')
        </form>
    @endif
</div> 
<p class="my-4 mx-10">@lang('subscriptions::admin/users.registered') {{ $user->created_at->format('d.m.Y') }}</p>

<p class="my-4 mx-10">@lang('subscriptions::admin/users.role'): @lang('app.role'.$user->role)</p>

<h2 class="sub-title">@lang('subscriptions::admin/users.supportMessages')</h2>

        <div class="flex my-4 mx-10">
            <div class="mr-3 relative w-12 h-12 text-gray-500">
                <svg class="fill-current inline-block w-12 h-12" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="48"><path d="M6 14H4a2 2 0 0 1-2-2V4c0-1.1.9-2 2-2h12a2 2 0 0 1 2 2v2h2a2 2 0 0 1 2 2v13a1 1 0 0 1-1.7.7L16.58 18H8a2 2 0 0 1-2-2v-2zm0-2V8c0-1.1.9-2 2-2h8V4H4v8h2zm14-4H8v8h9a1 1 0 0 1 .7.3l2.3 2.29V8z"/></svg>
                @if($user->unreadSupportMessagesCount('user') > 0)
                    <div class="absolute text-sm top-0 right-0 mt-1">
                        <div class="text-red-600">●</div> 
                    </div>
                    <div class="absolute text-gray-700 top-0 left-0 w-12 mt-3 ml-1 text-center">
                        {{ $user->unreadSupportMessagesCount('user') }}
                    </div>
                @endif
            </div>
            <div class="">
                @if($user->support_messages()->count() > 0)
                    <div class="text-sm mt-1">
                        @lang('subscriptions::admin/users.lastMessage')
                    </div>
                    <div class="text-sm mt-1 hover:no-underline">
                        <a href="/support/dialog/{{ $user->id }}#read" class="hover:no-underline">
                            {!! $user->support_messages()->orderBy('created_at', 'DESC')->first()->message !!}
                        </a>
                    </div>
                @else
                    <a href="/support/dialog/{{ $user->id }}#read"><b>@lang('subscriptions::admin/users.openDialog')</b></a>
                @endif
            </div>
        </div>


<h2 class="sub-title">@lang('subscriptions::admin/users.subscription')</h2>

@if($user->subscription())
    <p class="my-4 mx-10 font-bold">@lang('subscriptions::admin/users.tariff') {{ $user->subscription()->name }}</p>

    <p class="my-2 mx-10">
        @foreach($user->subscription()->features as $feature)
            - @lang('subscriptions::features.'.$feature)<br>
        @endforeach
    </p>
@endif

<form autocomplete="off" method="post" action="/users/{{ $user->id }}/subscription">
    @csrf
    <div class="flex items-end">
        <div class="form-group flex-grow mt-8 @error('tariff') has-error @enderror">
            <label for="tariff">@lang('subscriptions::admin/users.changeTariff')</label>
            <select name="tariff" class="block">
                @foreach($tariffs as $tariff)
                    <option value="{{ $tariff->id }}" @if(old('tariff')==$tariff->id)selected=""@endif>{{ $tariff->name }}</option>
                @endforeach
            </select>
            @error('tariff')
                <div class="error-description">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <div class="form-group text-center">
            <button type="submit" class="button">@lang('subscriptions::admin/users.save')</button>
        </div>
    </div>
</form>

<h2 class="sub-title">@lang('subscriptions::admin/users.payments')</h2>

<table class="table-auto w-full mt-8 mb-4">
    <thead>
        <tr>
            <th>@lang('subscriptions::admin/users.date')</th>
            <th>@lang('subscriptions::admin/users.card')</th>
            <th>@lang('subscriptions::admin/users.amount')</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @foreach($user->payments()->orderBy('created_at', 'desc')->get() as $payment)
            <tr class="text-center odd:bg-gray-200">
                <td class="py-4">{{ $payment->created_at->format("d.m.Y") }}</td>
                <td>{{ $payment->card_last_digits }}</td>
                <td>{{ $payment->amount }} {{ $payment->currency }}</td>
                <td>
                    @if($payment->status == 'Completed')
                        <a class="button small light" href="/users/payments/{{ $payment->id }}/delete" onclick="return confirm('@lang('subscriptions::admin/users.confirmRefund')')">
                            @lang('subscriptions::admin/users.refund')
                        </a>
                    @else
                        @if($payment->status == 'Authorized')
                            @lang('subscriptions::admin/users.Authorized')
                        @endif
                        @if($payment->status == 'Refunded')
                            @lang('subscriptions::admin/users.Refunded')
                        @endif
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

@endsection
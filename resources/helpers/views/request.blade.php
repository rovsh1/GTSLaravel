<?= '<?php' ?>

/** ATTENTION! DO NOT MODIFY THIS FILE BECAUSE IT WAS GENERATED AUTOMATICALLY */

namespace PortGateway\{{$module}}@if(strlen($port)>0)\{{$port}}@endif;

class {{\Str::ucfirst($method)}}Request implements \GTS\Shared\Domain\Port\RequestInterface {
    public function __construct(
    @foreach($arguments as $index => $argument)
    public readonly {{$argument->isNullable ? '?' : ''}}@if(!$argument->isScalarType())\@endif{{$argument->type}} ${{$argument->name}}@if($argument->isDefaultValueAvailable) @if($argument->defaultValue===null) = null @else{{$argument->defaultValue}}@endif @endif,
    @endforeach
{!! ')' !!} {}

    public function module(): string {
        return '{{$module}}';
    }

    public function port(): string {
        return '{{$port}}';
    }

    public function method(): string {
        return '{{$method}}';
    }

    public function arguments(): array {
        return [
    @foreach($arguments as $argument)
        '{{$argument->name}}' => $this->{{$argument->name}},
    @endforeach
    ];
    }
}

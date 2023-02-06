<?= '<?php' ?>

namespace GTS\Services\PortGateway\Request\{{$module}}@if(strlen($port)>0)\{{$port}}@endif;

class {{\Str::ucfirst($method)}}Request implements \GTS\Shared\Domain\Port\RequestInterface {

    public function __construct(
    @foreach($arguments as $index => $argument)
    public readonly {{$argument->isNullable ? '?' : ''}}@if(!$argument->isScalarType())\@endif{{$argument->type}} ${{$argument->name}}@if($argument->isDefaultValueAvailable ? " = {$argument->defaultValue}" : '')@endif,
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

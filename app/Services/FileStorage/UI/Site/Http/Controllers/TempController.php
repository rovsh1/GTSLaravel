<?php

namespace GTS\Services\FileStorage\UI\Site\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Illuminate\Routing\Controller;

class TempController extends Controller
{
    private $storage;

    public function __construct()
    {
        $this->storage = Storage::disk('tmp');
    }

    public function upload(Request $request)
    {
        if (!$request->hasFile('file')) {
            $upload = $request->file('file');
            if ($upload)
                throw new \UnexpectedValueException($upload->getErrorMessage());
            else
                throw new \InvalidArgumentException('File undefined');
        }

        $upload = $request->file('file');
        $path = $upload->storeAs('', $upload->hashName(), 'tmp');

        return response()->json([
            'name' => $upload->getClientOriginalName(),
            'filename' => $path,
            'mime' => $upload->getMimeType(),
            'src' => '/tmp/' . $path
        ]);
    }

    public function unlink(Request $request, $tmpname)
    {
        $filename = $tmpname;
        if (!$this->storage->exists($filename))
            return abort(404);

        $this->storage->delete($filename);

        return 'ok';
    }

    public function tmp(Request $request, $tmpname)
    {
        $filename = $tmpname;
        if (!$this->storage->exists($filename))
            return abort(404);

        $response = Response::make($this->storage->get($filename));
        $response->headers->add([
            'Content-Type' => $this->storage->mimeType($filename),
        ]);

        return $response;
    }
}

<?php

namespace Chatway\LaravelCrudGenerator\Core\Generators;

use Chatway\LaravelCrudGenerator\Core\Base\Interfaces\GeneratorInterface;
use Chatway\LaravelCrudGenerator\Core\DTO\ResultGeneratorDTO;
use Chatway\LaravelCrudGenerator\Core\Entities\GeneratorForm;
use File;
use View;

class ModelGenerator implements GeneratorInterface
{
    const FOLDER_NAME = 'Entities';
    public $baseClassNs = 'App\Base\Models\\';
    public $baseClass   = 'AbstractModel';

    public function __construct(public GeneratorForm $generatorForm)
    {
    }

    public function generate(): ResultGeneratorDTO
    {
        $namespace = $this->getModelNs();
        View::addLocation(app('path') . '/Console/Generator/Templates/Classes');
        View::addNamespace('model', app('path') . '/Console/Generator/Templates/Classes');
        $renderedModel = View::make('model')->with(
            [
                'modelGenerator' => $this,
            ]);
        $filename = "{$this->generatorForm->resourceName}.php";
        $path = base_path(lcfirst($namespace));
        if (!File::isDirectory($path)) {
            File::makeDirectory($path, 0777, true, true);
        }
        File::delete($path . '\\' . $filename);
        return new ResultGeneratorDTO(
            [
                'success'  => File::put($path . '\\' . $filename, $renderedModel),
                'fileName' => $path . $filename,
                'filePath' => lcfirst($namespace) . '\\' . $filename,
                'modelNs'  => $namespace,
            ]);
    }

    public function getFullName()
    {
        return $this->generatorForm->baseNs . $this::FOLDER_NAME . '\\' . $this->generatorForm->resourceName;
    }

    public function getModelNs()
    {
        return $this->generatorForm->baseNs . $this::FOLDER_NAME;
    }

    public function getBaseClassWithNs()
    {
        return $this->baseClassNs . $this->baseClass;
    }
}

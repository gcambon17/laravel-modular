<table>
    <tr>
        <th>Name</th>
        <th>Activated</th>
        <th>Installed</th>
        <th>Configurable</th>
        <th>Actions</th>
    </tr>
    @foreach($modules as $module)
        <tr>
            <td>{{$module->getModule()->getName()}}</td>
            <td>@if($module->isActive()) TRUE @else FALSE @endif</td>
            <td>@if($module->isInstalled()) TRUE @else FALSE @endif</td>
            <td>@if($module->getModule()->isConfigurable()) TRUE @else FALSE @endif</td>
            <td>
                @if(!$module->isActive())

                    <a href="{{url('laravel-modular/'.$module->getModule()->getKey().'/activate')}}">Activate</a>
                @else
                    @if(!$module->isInstalled())
                        <a href="{{url('laravel-modular/'.$module->getModule()->getKey().'/install')}}">Install</a>
                        <a href="{{url('laravel-modular/'.$module->getModule()->getKey().'/desactivate')}}">Unactivate</a>
                    @else
                        <a href="{{url('laravel-modular/'.$module->getModule()->getKey().'/uninstall')}}">Uninstall</a>
                    @endif

                @endif
            </td>
        </tr>
    @endforeach
</table>
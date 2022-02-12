<?php

namespace App\Http\Controllers\Paneles;

use App\Models\Usuario;
use App\Models\Alarma;
use App\Models\Contenedor;
use App\Models\Empresa_contenedore;
use App\Models\Empresa;
use App\Models\Eventos;
use App\Models\Registro_diario_generadores;
use App\Models\Registro_diario_reefers;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PanelController extends Controller
{
  
    
    public function board()
    {
        date_default_timezone_set("America/Lima");
        $usuario = []; 
        if (session()->get('usuario') == null) {
            return redirect('/');
        }
        $actualizado =  Usuario::where('usuario',session()->get('usuario'))->update(['ultimo_acceso'=>date('Y-m-d H:i:s')]);
        if ($actualizado == 1) {
            $usuario = Usuario::where('usuario',session()->get('usuario'))->get();
        }
        if (count($usuario) != 0) {

            $empresaXusuario = Empresa::where('usuario_id',$usuario[0]->id)->get();
            $contenedores_todos = Empresa_contenedore::select('contenedores.id', 'contenedores.nombre_contenedor', 
                                        'contenedores.tipo', 'contenedores.encendido', 'empresas.id', 'empresas.usuario_id', 
                                        'empresas.nombre_empresa', 'empresas.descripcion_booking', 'empresas.temp_contratada')
                                        ->join('contenedores', 'contenedores.id', 'empresas_contenedores.contenedor_id')
                                        ->join('empresas', 'empresas.id', 'empresas_contenedores.empresa_id')
                                        ->get();
            $contenedores_encendidos = Empresa_contenedore::select('contenedores.id', 'contenedores.nombre_contenedor', 
                                        'contenedores.tipo', 'contenedores.encendido', 'empresas.id', 'empresas.usuario_id', 
                                        'empresas.nombre_empresa', 'empresas.descripcion_booking', 'empresas.temp_contratada')
                                        ->join('contenedores', 'contenedores.id', 'empresas_contenedores.contenedor_id')
                                        ->join('empresas', 'empresas.id', 'empresas_contenedores.empresa_id')
                                        ->where('contenedores.encendido','encendido')
                                        ->get();

            return Inertia::render('Panel/board', [
                'usuario_logeado' => $usuario,
                'empresa_logeado' => $empresaXusuario,
                'contenedores_todos' => $contenedores_todos,
                'contenedores_encendidos' => $contenedores_encendidos,
            ]);
        }
        
    }
    public function faker_datos()
    {
        # code...
        Registro_diario_generadores::insert([
            'contenedor_id' => 2,
            'nombre_contenedor' => 'Contenedor 2',
            'set_point' => rand((1*10),(100*10))/10,
            'temp_supply' => rand((1*10),(100*10))/10,
            'temp_return' => rand((1*10),(100*10))/10,
            're_hume' => rand((1*10),(100*10))/10,
            're_c_o2' => rand((1*10),(100*10))/10,
            're_o2' => rand((1*10),(100*10))/10,
            'alv' => rand(10,1000),
            'latitud'=> -08.5,
            'longitud' =>-79.5,
            'status' => 'on',
            'modelo' => 'ThermoKing',
        ]);

        Registro_diario_reefers::insert([
            'contenedor_id' => 1,
            'nombre_contenedor' => 'Contenedor 1',
            'set_point' => rand((1*10),(100*10))/10,
            'temp_supply' => rand((1*10),(100*10))/10,
            'temp_return' => rand((1*10),(100*10))/10,
            're_hume' => rand((1*10),(100*10))/10,
            'fuel_level' => rand(10,100),
            'vdc' => rand(10,100),
            'rpm' => rand(10,1000),
            'freq' => rand(10,100),
            'vac' => rand(10,100),
            'latitud'=> -08.5,
            'longitud' =>-79.5,
            'temp_motor' => rand((1*10),(1000*10))/10,
            'status' => 'on',
            'speed' => rand(10,100),
            'ecopower' => 'on',
            'horometro' => rand(10,100),
            'alamra_id' =>rand(1,24),
            'evento_id' => rand(1,25),
            'modelo' => 'ThermoKing',
        ]);
    }


    //http://162.248.55.24/trace/AWS981GHABT2X/ZGRU101342-0?setpoint=4.10&tsupply=23.50&treturn=23.50&rehume=32766&reco2=3276.70&reo2=3276.70&avl=32767&latitude=-08.5&longitude=-79.5&status=off&model=ThermoKing
    /* DATOS ENVIADOS POR GENERADOR
    trace
    AWS981GHABT2X
    ZGRU101342-0
    setpoint=4.10 
    tsupply=23.50
    treturn=23.50
    rehume=32766
    reco2=3276.70
    reo2=3276.70
    avl=32767
    latitude=-08.5
    longitude=-79.5
    status=
    model=ThermoKing
    */
    //http://162.248.55.24/trace/AWS981GHABT2X/ZGUU205301-0?setpoint=0.00&tsupply=0.00&treturn=0.00&rehume=0.00&fuel=0.00&vdc=0.00&rpm=0&freq=0&vac=0&latitude=&longitude=&tmotor=0.00&status=off&speed=low&ecopower=off&horometro=0&alarma.0=&event.0=&model=thermoking"
    /* DATOS ENVIADOS POR REEFER
    trace
    AWS981GHABT2X
    ZGUU205301-0
    setpoint=0.00
    tsupply=0.00
    treturn=0.00
    rehume=0.00
    fuel=0.00
    vdc=0.00
    rpm=0
    freq=0
    vac=0
    latitude=
    longitude=
    tmotor=0.00
    status=off
    speed=low
    ecopower=off
    horometro=0
    alarma.0=
    event.0=
    model=thermoking
    */
}

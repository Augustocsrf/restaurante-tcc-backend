<?php
namespace App\Http\Middleware;

use App\User;
use Closure;

class EnsureTokenIsValid
{
    

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, ...$roles)
    {
        $roleKey = [
            'client' => 1,
            'staff' => 2,
            'admin' => 3
        ];

        //Obter token de autorização
        $authorization = $request->header('Authorization');

        //Se o token não for informado, retornar erro
        if(!$authorization){
            return response()
                ->json([ 'message' => 'Token não informado'], 401);
        }

        //Obter token
        $api_token = str_replace("Bearer ", "", $authorization);

        //Encontrar usuário que utiliza o token
        $user = User::where('api_token', $api_token)->first();

        //Caso usuário não tenha sido encontrado, prosseguir com o pedido
        if(!$user){
            return response()
                ->json([ 'message' => 'Token inválido' ], 401);
        }

        //Validar a permissão do usuário informado antes de permitir que ele prossiga 
        //Caso o array de funções permitidas estiver vazios quer dizer que qualquer uma pode usar e passar automaticamente
        if(count($roles) == 0){
            //Adicionar informações do usuário ao pedido
            $request->user = $user;

            return $next($request);
        }

        //Se não prosseguir e verificar entre as funções informadas se alguma se compara a função do cliente 
        foreach ($roles as $role) {
            if($user->permission == $roleKey[$role]){
                //Adicionar informações do usuário ao pedido
                $request->user = $user;

                return $next($request);
            }
        }

        return response()
                ->json([ 'message' => 'Este usuário não tem as permissões necessárias' ], 401);
        /*
        //Adicionar informações do usuário ao pedido
        $request->user = $user;

        return $next($request);
        */
    }
}

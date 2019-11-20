<?php 
	class validate {
		function cnpj($cnpj){
		    $cnpj = preg_replace( '/[^0-9]/', '', $cnpj );
		    $cnpj = (string)$cnpj;
		    $cnpj_original = $cnpj;
		    $primeiros_numeros_cnpj = substr( $cnpj, 0, 12 );
		    
		    if ( ! function_exists('multiplica_cnpj') ) {
		        function multiplica_cnpj( $cnpj, $posicao = 5 ) {
					$calculo = 0;
		            for ( $i = 0; $i < strlen( $cnpj ); $i++ ) {
		                $calculo = $calculo + ( $cnpj[$i] * $posicao );
		                $posicao--;
		                if ( $posicao < 2 ) {
		                    $posicao = 9;
		                }
		            }
		            return $calculo;
		        }
		    }
		    $primeiro_calculo = multiplica_cnpj( $primeiros_numeros_cnpj );
		    $primeiro_digito = ( $primeiro_calculo % 11 ) < 2 ? 0 :  11 - ( $primeiro_calculo % 11 );
		    $primeiros_numeros_cnpj .= $primeiro_digito;
		    $segundo_calculo = multiplica_cnpj( $primeiros_numeros_cnpj, 6 );
		    $segundo_digito = ( $segundo_calculo % 11 ) < 2 ? 0 :  11 - ( $segundo_calculo % 11 );
		    $cnpj = $primeiros_numeros_cnpj . $segundo_digito;

		    if ( $cnpj === $cnpj_original ) {
		        return true;
		    }else{
		    	return false;
		    }
		}

		function cpf($cpf = false){

		    if ( ! function_exists('calc_digitos_posicoes') ) {
		        function calc_digitos_posicoes( $digitos, $posicoes = 10, $soma_digitos = 0 ) {
		           for ( $i = 0; $i < strlen( $digitos ); $i++  ) {
		                $soma_digitos = $soma_digitos + ( $digitos[$i] * $posicoes );
		                $posicoes--;
		            }
		     		$soma_digitos = $soma_digitos % 11;
		     		if ( $soma_digitos < 2 ) {
		               $soma_digitos = 0;
		            } else {
		               $soma_digitos = 11 - $soma_digitos;
		            }
		     		$cpf = $digitos . $soma_digitos;
		            return $cpf;
		        }
		    }
		    
		    
		    if ( ! $cpf ) {
		        return false;
		    }

		    $cpf = preg_replace( '/[^0-9]/is', '', $cpf );

		    if ( strlen( $cpf ) != 11 ) {
		        return false;
		    }   

		    $digitos = substr($cpf, 0, 9);
			$novo_cpf = calc_digitos_posicoes( $digitos );
			$novo_cpf = calc_digitos_posicoes( $novo_cpf, 11 );

		    if ( $novo_cpf === $cpf ) {
		     	return true;
		    } else {
		     	return false;
		    }
		}
	}
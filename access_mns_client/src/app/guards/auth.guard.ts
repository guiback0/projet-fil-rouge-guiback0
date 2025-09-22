import { inject } from '@angular/core';
import { CanActivateFn, Router } from '@angular/router';
import { AuthenticationService } from '../services/auth/authentication.service';

/**
 * Guard d'authentification pour protéger les routes
 * Vérifie si l'utilisateur est authentifié avant d'autoriser l'accès
 */
export const authGuard: CanActivateFn = (_, state) => {
  const authenticationService = inject(AuthenticationService);
  const router = inject(Router);

  // Vérifier si l'utilisateur est authentifié
  if (authenticationService.isAuthenticated()) {
    return true;
  }

  // Rediriger vers la page de connexion si non authentifié
  router.navigate(['/login'], {
    queryParams: { returnUrl: state.url }
  });
  
  return false;
};
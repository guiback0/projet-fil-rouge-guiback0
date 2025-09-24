export const environment = {
  production: false,
  // Pour le développement local, utiliser les URLs relatives qui passent par le proxy nginx
  // Pour Railway, utiliser les URLs absolues vers le backend
  apiBaseUrl: typeof window !== 'undefined' && window.location.hostname.includes('railway.app') 
    ? 'https://access-mns-manager-qa.up.railway.app/manager/api'
    : '/manager/api',
  apiUrl: typeof window !== 'undefined' && window.location.hostname.includes('railway.app')
    ? 'https://access-mns-manager-qa.up.railway.app/manager/api'
    : '/manager/api'
};
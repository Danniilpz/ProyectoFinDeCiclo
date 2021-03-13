
package bot; 

import com.google.gson.JsonObject;
import com.google.gson.JsonParser;
import java.io.BufferedReader;
import java.io.FileWriter;
import java.io.IOException;
import java.io.InputStreamReader;
import java.io.PrintWriter;
import java.net.HttpURLConnection;
import java.net.MalformedURLException;
import java.net.URISyntaxException;
import java.net.URL;
import java.util.ArrayList;
import java.util.Arrays;
import java.util.Calendar;
import java.util.Collections;
import java.util.HashMap;
import java.util.List;
import java.util.Map;
import java.util.logging.Level;
import java.util.logging.Logger;
import org.jsoup.Jsoup;
import org.apache.commons.lang3.StringUtils;

public class Bot implements Runnable{
    
    private String direccion_actual; //direccion que se va a inspeccionar en una instancia
    private ArrayList<String> direcciones,imagenes; 
    private static Map<String,String> urlsinspeccionadas=new HashMap(); //array de URLs y sitios ya inspeccionados en cada vez
    public static DAO dao=new DAO(); //Es una variable pública, para poder cerrar la conexión desde la clase Ventana.

    public Bot(String direccion_actual) {
        this.direccion_actual = direccion_actual;
    }    
    
    public static void main(String[] args) {
        Ventana v=new Ventana();
        String sitio;
        try {
            dao.abrirConexion();
            ArrayList<String> sitios=dao.listadoSitiosWebExpirados();
            ArrayList<Thread> hilos=new ArrayList();
            Thread hilo;
            Calendar hora;
            while(true){
                hora = Calendar.getInstance(); 
                if(((hora.get(Calendar.HOUR_OF_DAY)==0||hora.get(Calendar.HOUR_OF_DAY)==12))&&hora.get(Calendar.MINUTE)==00&&hora.get(Calendar.SECOND)==0&&hora.get(Calendar.MILLISECOND)==0){
                    dao.optimizarKeywords();
                }  
                if(isOn()){
                    v.p.setOn();
                    if(sitios.size()>0&&Thread.activeCount()<22){ //controlo que haya como máximo 20 robots al mismo tiempo
                        sitio=sitios.get(0);
                        if(analizaURL(sitio)){
                            Runnable proceso=new Bot(sitio);
                            hilo=new Thread(proceso);
                            hilos.add(hilo);
                            hilo.start();
                            dao.bloquearSitio(sitio);
                        }
                        else{
                            dao.eliminarSitio(sitio);
                        }
                    }
                    sitios=dao.listadoSitiosWebExpirados();
                }
                else{
                    v.p.setOff();
                    for(int i=0;i<hilos.size();i++){
                         hilos.get(i).stop();
                    }
                    dao.desbloquearTodosSitios();
                }
                
            }  
            //dao.cerrarConexion();
        } catch (Exception ex) {
            Logger.getLogger(Bot.class.getName()).log(Level.SEVERE, null, ex);
        }
    }
    
    @Override
    public void run() {
        try {
            urlsinspeccionadas.values().removeAll(Collections.singleton(direccion_actual)); //elimino del array de inspeccionados todos los enlaces de este sitio, para volver a inspeccionarlos
            direcciones=dao.listadoDireccionesPorSitio(direccion_actual);
            inspeccionarURL(direccion_actual);
            if(!direcciones.isEmpty()){
                for(int j=0;j<direcciones.size();j++){
                    dao.eliminarDireccion(new Direccion(direccion_actual,direcciones.get(j),obtenerTitulo(direcciones.get(j))));
                }
            }
            dao.actualizarFechaExpiracion(direccion_actual);
            dao.desbloquearSitio(direccion_actual);
        } catch (Exception ex) {
            Logger.getLogger(Bot.class.getName()).log(Level.SEVERE, null, ex);
        }
    }
        
    private static boolean robotsAllowed(String url){
        String strHost;
        try {
            strHost = new URL(url).getHost();
        } catch (MalformedURLException ex) {
            return false;
        }

        String strRobot = "http://" + strHost + "/robots.txt";
        String strCommands="";
        try {
            Process p2 = Runtime.getRuntime().exec("curl -H 'Cache-Control: no-cache;Content-Type: text/plain; charset=UTF-8' -L -A \"LoopzBot\" "+strRobot); //abro la url a inspeccionar
            BufferedReader in2=new BufferedReader(new InputStreamReader(p2.getInputStream()));  
            String line2=null;  
            while((line2=in2.readLine())!=null){
                strCommands+=line2+"\n";
            } 
        } catch (IOException ex) {
            Logger.getLogger(Bot.class.getName()).log(Level.SEVERE, null, ex);
        }          

        if (strCommands.contains("Disallow")) // if there are no "disallow" values, then they are not blocking anything.
        {
            String[] split = strCommands.split("\n");
            ArrayList<RobotRule> robotRules = new ArrayList<>();
            String mostRecentUserAgent = null;
            for (int i = 0; i < split.length; i++) 
            {
                String line = split[i].trim();
                if (line.toLowerCase().startsWith("user-agent")) 
                {
                    int start = line.indexOf(":") + 1;
                    int end   = line.length();
                    mostRecentUserAgent = line.substring(start, end).trim();
                }
                else if (line.startsWith("Disallow")) {
                    if (mostRecentUserAgent != null) {
                        RobotRule r = new RobotRule();
                        r.userAgent = mostRecentUserAgent;
                        int start = line.indexOf(":") + 1;
                        int end   = line.length();
                        r.rule = line.substring(start, end).trim();
                        robotRules.add(r);
                    }
                }
            }

            for (RobotRule robotRule : robotRules)
            {
                if(robotRule.userAgent.equals("*")||robotRule.userAgent.equals("LoopzBot")){
                    String path;
                    try {
                        path = new URL(url).getPath();
                    } catch (MalformedURLException ex) {
                        return false;
                    }
                    if (robotRule.rule.length() == 0) return true; // allows everything if BLANK
                    if (robotRule.rule.equals("/")) return false;       // allows nothing if /

                    if (robotRule.rule.length() <= path.length())
                    { 
                        String pathCompare = path.substring(0, robotRule.rule.length());
                        if (pathCompare.equals(robotRule.rule)) return false;
                    }
                }               
            }
        }
        return true;
    }
       
    private boolean inspeccionarURL(String url){
        botLog("Entrando\t["+url+"]");
        boolean valida=true; //variable que determina si la url a inspeccionar es valida o no
        String line=null,sitioweb; 
        
        ArrayList<String> urlsainspeccionar=new ArrayList();
        try {   
            sitioweb=obtenerSitio(url);
            if(robotsAllowed(url)){                
                if(urlExists(url)){ //compruebo si existe la URL 
                    if(!urlYaExistente(url,sitioweb)){ 
                        botLog("Inspeccionando\t["+url+"]");
                        try {
                            if(dao.guardarDireccion(new Direccion(sitioweb,url,obtenerTitulo(url)))==0){
                                valida=false;
                            }
                        } catch (Exception ex) {
                            Logger.getLogger(Bot.class.getName()).log(Level.SEVERE, null, ex);
                        }
                    }
                    else{
                        botLog("Actualizando\t["+url+"]");
                        try {
                            dao.actualizarTitulo(new Direccion(sitioweb,url,obtenerTitulo(url)));
                        } catch (Exception ex) {
                            Logger.getLogger(Bot.class.getName()).log(Level.SEVERE, null, ex);
                        }
                        direcciones.remove(url);
                    }
                }
                else{
                    valida=false; //si no existe, no es válida
                    eliminarURL(url,sitioweb);
                }
                 
                
                urlsinspeccionadas.put(url,sitioweb); //guardo en el arraylist el enlace para que no
                                              //lo vuelva a examinar en el proceso recursivo
                if(valida==true){                    
                    ArrayList<String> links_encontrados=new ArrayList(); //array que almacenará los enlaces que se encuentren
                    ArrayList<String> imagenes_encontradas=new ArrayList(); //array que almacenará las imagenes que se encuentren
                    Process p2=Runtime.getRuntime().exec("curl -H 'Cache-Control: no-cache;Content-Type: text/plain; charset=UTF-8' -L -A \"LoopzBot\" "+url);  //abro la url a inspeccionar
                    
                    BufferedReader in2=new BufferedReader(new InputStreamReader(p2.getInputStream()));  
                    String line2,link_encontrado,imagen_encontrada,enlacetemp;  
                    while((line2=in2.readLine())!=null){ 
                        if(line2.contains("href=")&&line2.contains("<a")&&line2.indexOf('#')==-1){
                            //si la linea contiene un href=, un <a, y no contiene # (para evitar enlaces locales)
                            enlacetemp=obtenerEnlace(line2,"href");
                            if(enlacetemp!=null){
                                links_encontrados.add(enlacetemp); //extrae el enlace encontrado de la linea
                            }
                        }
                        else if(line2.contains("window.location.href")){
                            //si la linea contiene una redireccion javascript
                            enlacetemp=obtenerEnlace(line2,"location");
                            if(enlacetemp!=null){
                                links_encontrados.add(enlacetemp); //extrae el enlace encontrado de la linea
                            }
                        }
                        if(line2.contains("src=")&&line2.contains("<img")){
                            enlacetemp=obtenerEnlace(line2,"src");
                            if(enlacetemp!=null){
                                imagenes_encontradas.add(enlacetemp); //extrae el enlace encontrado de la linea
                            }
                        }
                    } 
                    
                    if(!links_encontrados.isEmpty()){ //si el array de links encontrados no esta vacio
                        for(int i=0;i<links_encontrados.size();i++){
                            if(links_encontrados.get(i).contains(new URL(sitioweb).getHost())|| //si el link tiene el host
                                    links_encontrados.get(i).contains("//")|| //tiene doble barra
                                    links_encontrados.get(i).contains("http://")||
                                    links_encontrados.get(i).contains("https://")){ // o un protocolo, ya esta completo

                                if(!links_encontrados.get(i).contains("http")&&
                                   !links_encontrados.get(i).contains("https")&&
                                    links_encontrados.get(i).contains("//")){ //si tiene // pero no tiene el protocolo, se lo añado
                                    link_encontrado=new URL(sitioweb).getProtocol()+":"+links_encontrados.get(i);
                                }
                                else{ 
                                    link_encontrado=links_encontrados.get(i); //si tiene protocolo, //, y host, está bien                                
                                }
                            }
                            else{ //si no, lo completo con la direccion del sitio web
                                if(links_encontrados.get(i).length()>0&&links_encontrados.get(i).charAt(0)=='/'){
                                    link_encontrado=sitioweb+links_encontrados.get(i).substring(1);
                                }
                                else{
                                    link_encontrado=sitioweb+links_encontrados.get(i);
                                } 
                            }
                            try{
                                URL link_enc=new URL(link_encontrado);
                                link_encontrado=link_enc.getProtocol()+"://"+link_enc.getHost()+link_enc.getPath();
                                if(link_encontrado.contains(sitioweb)&& //si el enlace es local(no de un web externa)
                                        !urlsinspeccionadas.containsKey(link_encontrado)){ //y si no se encuentra entre las urls ya inspeccionadas

                                    urlsainspeccionar.add(link_encontrado); //guarda la url en el array de urls a inspeccionar
                                }
                                else if (!link_encontrado.contains(sitioweb)){   
                                    link_encontrado=obtenerSitio(link_encontrado);
                                    if(urlExists(link_encontrado)){                 
                                        try {         
                                            if(!sitioYaExistente(link_encontrado)){
                                                dao.guardarSitio(link_encontrado);
                                            }
                                        } catch (Exception ex) {
                                            //Cuando dos threads concurren al comprobar si un sitio existe, 
                                            //es posible que a ambos les devuelva false e intenten insertarlo, devolviendo excepción de duplicidad en uno de ellos.
                                            //Esta excepción está controlada y no genera ningún problema.
                                        }
                                    }
                                }
                            }
                            catch(MalformedURLException ex){
                                //Si la URL no es válida, salta la excepción y no la tiene en cuenta.
                            }                            
                        }
                    }
                    try{
                        imagenes=dao.listadoImagenesPorDireccion(url);
                        if(!imagenes_encontradas.isEmpty()){ //si el array de links encontrados no esta vacio
                            for(int i=0;i<imagenes_encontradas.size();i++){
                                if(imagenes_encontradas.get(i).contains(new URL(sitioweb).getHost())|| //si el link tiene el host
                                        imagenes_encontradas.get(i).contains("//")|| //tiene doble barra
                                        imagenes_encontradas.get(i).contains("http://")||
                                        imagenes_encontradas.get(i).contains("https://")){ // o un protocolo, ya esta completo

                                    if(!imagenes_encontradas.get(i).contains("http")&&
                                       !imagenes_encontradas.get(i).contains("https")&&
                                        imagenes_encontradas.get(i).contains("//")){ //si tiene // pero no tiene el protocolo, se lo añado
                                        imagen_encontrada=new URL(sitioweb).getProtocol()+":"+imagenes_encontradas.get(i);
                                    }
                                    else{ 
                                        imagen_encontrada=imagenes_encontradas.get(i); //si tiene protocolo, //, y host, está bien                                
                                    }
                                }
                                else{ //si no, lo completo con la direccion del sitio web
                                    if(imagenes_encontradas.get(i).length()>0&&imagenes_encontradas.get(i).charAt(0)=='/'){
                                        imagen_encontrada=sitioweb+imagenes_encontradas.get(i).substring(1);
                                    }
                                    else{
                                        imagen_encontrada=sitioweb+imagenes_encontradas.get(i);
                                    } 
                                }
                                try{
                                    URL img_enc=new URL(imagen_encontrada);
                                    imagen_encontrada=img_enc.getProtocol()+"://"+img_enc.getHost()+img_enc.getPath();
                                    if(imagenYaExistente(imagen_encontrada,url)){
                                        imagenes.remove(imagen_encontrada);
                                    }
                                    else{
                                        if(urlExists(imagen_encontrada)&&esImagen(imagen_encontrada)){
                                            dao.guardarImagen(imagen_encontrada, url);
                                        }                                                                        
                                    }
                                }
                                catch(Exception ex){
                                }                            
                            }
                        }                        
                        if(!imagenes.isEmpty()){
                            for(int j=0;j<imagenes.size();j++){
                                dao.eliminarImagen(imagenes.get(j), url);
                            }
                        }
                    } catch(Exception ex) {
                        System.out.println(ex);
                    }
                    inspeccionarKeywords(url);
                    if(!urlsainspeccionar.isEmpty()){
                        for(int i=0;i<urlsainspeccionar.size();i++){
                            if(analizaURL(urlsainspeccionar.get(i))){
                                inspeccionarURL(urlsainspeccionar.get(i));
                            }
                        }
                    }
                }
            }
            else{
                valida=false;
                eliminarURL(url,sitioweb);
            }
        }catch(IOException ex){  
            Logger.getLogger(Bot.class.getName()).log(Level.SEVERE, null, ex);
        }
        return valida;
    }
    private static String obtenerSitio(String direccion) throws MalformedURLException{
        URL url = new URL(direccion);
        return url.getProtocol()+"://"+url.getHost()+"/";
    }
    private static String obtenerEnlace(String linea,String tipo){
        String link_encontrado=null;
        if(tipo.equals("src")||tipo.equals("href")){
            int taginicio=linea.indexOf(tipo+"="),tagfin;
            if(taginicio!=-1){
                if(taginicio+2+tipo.length()<linea.length()){
                    linea=linea.substring(taginicio+2+tipo.length()); //recorto el enlace quitando el href= o src= y la primera comilla
                    tagfin=linea.indexOf('"');
                    if(tagfin!=-1){
                        link_encontrado=linea.substring(0,tagfin);
                    }
                    else{
                        tagfin=linea.indexOf("'");
                        if(tagfin!=-1){
                            link_encontrado=linea.substring(0,tagfin);
                        }
                        else{
                            tagfin=linea.indexOf(' ');
                            if(tagfin!=-1){
                                link_encontrado=linea.substring(0,tagfin);
                            }
                        }
                    }
                }            
            }
        }
        else{
            int taginicio=linea.indexOf("\""),tagfin;
            if(taginicio==-1){
                taginicio=linea.indexOf("\'");
            }
            if(taginicio!=-1){
                linea=linea.substring(taginicio+1);
                tagfin=linea.indexOf("\"");
                if(tagfin==-1){
                    tagfin=linea.indexOf("\'");
                }
                if(tagfin!=-1){
                    link_encontrado=linea.substring(0,tagfin);
                }
            }
        }
        return link_encontrado;
    }

    private static boolean urlYaExistente(String url,String sitio){
        boolean encontrado=false;
        try {
            Process p=Runtime.getRuntime().exec("curl -Ls -w %{url_effective} -o /dev/null "+url); //obtengo la url redirigida
            BufferedReader in=new BufferedReader(new InputStreamReader(p.getInputStream()));  
            String url2=in.readLine();
            ArrayList<String> direcciones=dao.listadoDireccionesPorSitio(sitio);
            if(direcciones.contains(url)||direcciones.contains(url2)){
                encontrado=true;
            }
        } catch (Exception ex) {
            Logger.getLogger(Bot.class.getName()).log(Level.SEVERE, null, ex);
        }
        return encontrado;
    }
    private static boolean imagenYaExistente(String imagen,String direccion){
        boolean encontrado=false;
        try {
            ArrayList<String> images=dao.listadoImagenesPorDireccion(direccion);
            if(images.contains(imagen)){
                encontrado=true;
            }
        } catch (Exception ex) {
            Logger.getLogger(Bot.class.getName()).log(Level.SEVERE, null, ex);
        }
        return encontrado;
    }
    private static boolean sitioYaExistente(String sitio){
        boolean encontrado=false;
        try {
            ArrayList<String> sitios=dao.listadoSitiosWeb();
            if(sitios.contains(sitio)){
                encontrado=true;
            }
        } catch (Exception ex) {
            Logger.getLogger(Bot.class.getName()).log(Level.SEVERE, null, ex);
        }
        return encontrado;
    }
    private static boolean sitioIndexado(String sitio){
        boolean indexado=false;
        try {
            ArrayList<String> direcciones=dao.listadoDireccionesPorSitio(sitio);
            if(direcciones.size()>0){
                indexado=true;
            }
        } catch (Exception ex) {
            Logger.getLogger(Bot.class.getName()).log(Level.SEVERE, null, ex);
        }
        return indexado;
    }
    private static boolean urlValidator(String url){
        /*validación de url*/
        try {
            new URL(url).toURI();
            return true;
        }
        catch (URISyntaxException | MalformedURLException exception) {
            return false;
        }
    }
    private static boolean urlExists(String url){
        boolean existe=false;
        if(urlValidator(url)){
            try {
                HttpURLConnection connection = (HttpURLConnection)new URL(url).openConnection();
                connection.addRequestProperty("User-Agent", "Mozilla/5.0 (Windows NT 6.1; WOW64; rv:221.0) Gecko/20100101 Firefox/31.0");
                connection.setRequestMethod("GET");
                connection.connect();                
                int code = connection.getResponseCode();
                if(code==200){
                    existe=true;
                }
            } catch (MalformedURLException ex) {
                existe=false;
            } catch (IOException ex) {
                existe=false;
            } catch (Exception ex) {
                existe=false;
            }
        }
        return existe;
    } 
    private static String html2text(String html) {
        return Jsoup.parse(html).text();
    }
    private static String eliminaSignosDePuntuacion(String cadena){
        String texto=cadena;
        texto=texto.replaceAll("[^a-zA-Z0-9 ]", " ");
        return texto;
    }
    
    private static String obtenerTitulo(String direccion){
        String title="Página web sin título"; //Si no se encuentra título, este será el título por defecto
        try {
            String url;
            url=direccion;
            Process p2=Runtime.getRuntime().exec("curl -H 'Cache-Control: no-cache;Content-Type: text/plain; charset=UTF-8' -L -A \"LoopzBot\" "+url);  //abro la url a inspeccionar
            BufferedReader in2=new BufferedReader(new InputStreamReader(p2.getInputStream()));  
            String html="",line2;  
            while((line2=in2.readLine())!=null){ 
                html+=line2;
            }
            int inicio,fin;
            inicio=html.indexOf("<title");
            if(inicio!=-1){
                html=html.substring(inicio);
                fin=html.indexOf("</title>");
                if(fin!=-1){
                    title=html.substring(0,fin);
                    title=html2text(title);
                }
            }            
        } catch (IOException ex) {
            Logger.getLogger(Bot.class.getName()).log(Level.SEVERE, null, ex);
        }
        return title;
    }
    
    private static void inspeccionarKeywords(String direccion){
        try {
            ArrayList<String> direcciones=dao.listadoDirecciones();
            String url;
            Map<String, Integer> contpalabras = new HashMap();
            String[] palabras;
            Palabra p;
            int prioridad;
            ArrayList<Palabra> palabras_existentes=dao.listadoKeywordsPorDireccion(direccion);
            contpalabras.clear();
            url=direccion;
            Process p2=Runtime.getRuntime().exec("curl -H 'Content-Type: text/plain; charset=UTF-8;Cache-Control: no-cache' -L -A \"LoopzBot\" "+url);  //abro la url a inspeccionar
            BufferedReader in2=new BufferedReader(new InputStreamReader(p2.getInputStream()));  
            String line2,html="",title;  
            while((line2=in2.readLine())!=null){ 
                html+=line2;
            }
            int inicio,fin,inicio2,fin2;
            inicio=html.indexOf("<title");
            fin=html.lastIndexOf("</title>");
            if(inicio!=-1&&fin!=-1){
                title=html.substring(inicio,fin);
                title=html2text(title);
                title=eliminaSignosDePuntuacion(title);  
                title=StringUtils.stripAccents(title);
                palabras=title.split(" ");
                for(int j=0;j<palabras.length;j++){ //si las palabras están en el titulo, tienen más prioridad
                    if(!palabras[j].trim().equals("")){
                        contpalabras.put(palabras[j].toLowerCase(), contpalabras.containsKey(palabras[j].toLowerCase()) ? contpalabras.get(palabras[j].toLowerCase()) + 10 : 10);
                    }
                }   
                contpalabras.remove(""); //elimino palabras vacías  
            }
            inicio2=html.indexOf("<body");
            fin2=html.lastIndexOf("</body>");
            if(inicio2!=-1&&fin2!=-1){
                html=html.substring(inicio2,fin2);
                html=html2text(html);  
                html=StringUtils.stripAccents(html);
                html=eliminaSignosDePuntuacion(html);   
                palabras=html.split(" ");
                for(int j=0;j<palabras.length;j++){
                    contpalabras.put(palabras[j].toLowerCase(), contpalabras.containsKey(palabras[j].toLowerCase()) ? contpalabras.get(palabras[j].toLowerCase()) + 1 : 1);
                }
                contpalabras.remove(""); //elimino palabras vacías
            }            
            Map<String, Integer> contpalabras2=new HashMap();
            contpalabras2.putAll(contpalabras);
            for (Map.Entry<String, Integer> entry : contpalabras.entrySet()) { //recorre el mapa de palabras encontradas
                p=new Palabra(entry.getKey(),direccion,entry.getValue()); //palabra actual
                if(palabras_existentes.contains(p)){ //si la palabra ya estaba registrada
                    prioridad=palabras_existentes.get(palabras_existentes.indexOf(p)).getPrioridad();
                    palabras_existentes.remove(p);
                    contpalabras2.remove(p.getKeyword(), p.getPrioridad());
                    if(prioridad!=p.getPrioridad()){                            
                        dao.actualizarPrioridadKeyword(new Palabra(p.getKeyword(), p.getDireccion(), p.getPrioridad()));
                    }                         
                }
            } 
            contpalabras.clear();
            contpalabras.putAll(contpalabras2);
            dao.guardarKeyWords(contpalabras, direccion);
            if(palabras_existentes.size()>0){
                dao.eliminarKeywords(palabras_existentes);
            }
        } catch (Exception ex) {
            Logger.getLogger(Bot.class.getName()).log(Level.SEVERE, null, ex);
        } 
    }
    private static boolean analizaURL(String url){
        boolean segura=false;
        try {
            Process p=Runtime.getRuntime().exec("curl --request GET --url \"https://www.virustotal.com/vtapi/v2/url/report?apikey=78e14b2193146ffb52a9cef406a25fb1a5c677a0502eb7466fc0aaaa41f3d43d&resource="+url+"\"");
            BufferedReader in=new BufferedReader(new InputStreamReader(p.getInputStream()));
            String linea,respuesta="";
            int positives;
            while((linea=in.readLine())!=null){
                respuesta+=linea;
            }
            if(!respuesta.equals("")){ //al no tener la API premium, solo permite 4 analisis/minuto. En el resto devuelve respuesta vacía.
                JsonParser parser = new JsonParser();
                JsonObject obj = parser.parse(respuesta).getAsJsonObject();                
                try{
                    positives = obj.get("positives").getAsInt();
                }catch(Exception e){
                    positives=0;  
                }
            }
            else{
                positives=0;  
            }
            
            if(positives==0){
                segura=true;
            }
            
        } catch (Exception ex) {
            segura=true;
        }
        return segura;
    }
    private static boolean esImagen(String url){
        List<String> list = Arrays.asList("jpeg","jpg","png","gif","tiff","tif","raw","bmp");
        boolean resultado=false;
        if(url.lastIndexOf(".")!=-1){
            if(list.contains(url.substring(url.lastIndexOf(".")+1))){
                resultado=true;
            }
        }
        return resultado;
    }
    private static void eliminarURL(String url,String sitioweb){
        try {
            if(url.equals(sitioweb)&&!sitioIndexado(sitioweb)){ //si la url es la principal de un sitio, y ese sitio no está indexado, elimina el sitio entero
                botLog("Eliminando\t["+url+"]");
                dao.eliminarSitio(url); 
            }
            else if(urlYaExistente(url,sitioweb)){
                botLog("Eliminando\t["+url+"]");
                dao.eliminarDireccion(new Direccion(sitioweb,url));
            }
        } catch (Exception ex) {
            Logger.getLogger(Bot.class.getName()).log(Level.SEVERE, null, ex);
        }
    }
    private static void botLog(String mensaje){
        FileWriter fichero=null;
        try {
            fichero = new FileWriter("bot.log",true);
            PrintWriter pw=new PrintWriter(fichero);
            pw.println(Fecha.date("yyyy-MM-dd HH:mm:ss")+" "+mensaje);
            pw.close();
        } catch (IOException ex) {
            Logger.getLogger(Bot.class.getName()).log(Level.SEVERE, null, ex);
        } finally {
            try {
                fichero.close();
            } catch (IOException ex) {
                Logger.getLogger(Bot.class.getName()).log(Level.SEVERE, null, ex);
            }
        }
    }
    private static boolean isOn(){
        boolean on;
        try {
            Process p=Runtime.getRuntime().exec("curl https://loopz.cf/robot_status");  //abro la url a inspeccionar
            BufferedReader in=new BufferedReader(new InputStreamReader(p.getInputStream()));
            String linea,respuesta="",estado="";
            while((linea=in.readLine())!=null){
                respuesta+=linea;
            }           
            estado=html2text(respuesta);
            if(estado.equals("1")) on=true;
            else on=false;
        } catch (Exception ex) {
            on=false;
        }
        return on;
    }
}
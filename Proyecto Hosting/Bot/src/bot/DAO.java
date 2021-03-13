package bot;

import java.sql.*;
import java.util.ArrayList;
import java.text.SimpleDateFormat;
import java.util.Date;
import java.util.Map;

public class DAO {
    
    private Connection conexion;
    
    public void abrirConexion() throws Exception{
        Class.forName("com.mysql.jdbc.Driver");      
        conexion=DriverManager.getConnection("jdbc:mysql://loopz.cf:3306/loopzcf_db","loopzcf_admin","cifpponferrada");
        Statement sql = conexion.createStatement();     
    }
    
    public void cerrarConexion() throws Exception{
        conexion.close();  
    }
    public void optimizarKeywords() throws Exception{
        Statement sql = conexion.createStatement(); 
        sql.executeQuery ("OPTIMIZE TABLE keywords");             
    }
    public ArrayList<String> listadoSitiosWebExpirados() throws Exception{
        Statement sql = conexion.createStatement(); 
        ResultSet rs = sql.executeQuery ("SELECT direccion FROM sitios WHERE fecha_exp < NOW() AND bloqueado = 0 ORDER BY fecha_exp ASC");             
        ArrayList<String> v=new ArrayList();
        while(rs.next()){
            v.add(rs.getString("direccion"));                
        }
        
        return v;
    }
    
    public ArrayList<String> listadoSitiosWeb() throws Exception{
        Statement sql = conexion.createStatement(); 
        ResultSet rs = sql.executeQuery ("SELECT direccion FROM sitios");             
        ArrayList<String> v=new ArrayList();
        while(rs.next()){
            v.add(rs.getString("direccion"));                
        }
        
        return v;
    }
    
    public ArrayList<String> listadoDireccionesPorSitio(String sitio) throws Exception{
        Statement sql = conexion.createStatement(); 
        ResultSet rs = sql.executeQuery ("SELECT direccion FROM direcciones WHERE sitio='"+sitio+"'");             
        ArrayList<String> v=new ArrayList();
        while(rs.next()){
            v.add(rs.getString("direccion"));                
        }
        
        return v;
    }
    
    public ArrayList<String> listadoDirecciones() throws Exception{
        Statement sql = conexion.createStatement(); 
        ResultSet rs = sql.executeQuery ("SELECT direccion FROM direcciones");             
        ArrayList<String> v=new ArrayList();
        while(rs.next()){
            v.add(rs.getString("direccion"));                
        }        
        return v;
    }
    
    public int guardarSitio(String sitio) throws Exception{    
        int resultado=0;
        if(sitio.length()<=191){
            PreparedStatement sql=conexion.prepareStatement("insert into sitios values (?,NOW(),0)");
            sql.setString(1,sitio);  
            resultado=sql.executeUpdate();
        }
        return resultado;
    }
    
    public int bloquearSitio(String sitio) throws Exception{
        PreparedStatement sql=conexion.prepareStatement("update sitios set bloqueado=1 where direccion=?");
        sql.setString(1,sitio);
        int resultado=sql.executeUpdate();                       
        return resultado;
    }
    
    public int desbloquearSitio(String sitio) throws Exception{
        PreparedStatement sql=conexion.prepareStatement("update sitios set bloqueado=0 where direccion=?");
        sql.setString(1,sitio);
        int resultado=sql.executeUpdate();                       
        return resultado;
    }
    
    public int desbloquearTodosSitios() throws Exception{
        PreparedStatement sql=conexion.prepareStatement("update sitios set bloqueado=0");
        int resultado=sql.executeUpdate();                       
        return resultado;
    }
    
    public int guardarDireccion(Direccion d) throws Exception{ 
        int resultado;
        String titulo;
        if(d.getDireccion().length()<=191){
            titulo=d.getTitulo();
            if(titulo.length()>191){
                titulo=titulo.substring(0,191);
            }
            PreparedStatement sql=conexion.prepareStatement("insert into direcciones values (?,?,?)");
            sql.setString(1,d.getDireccion());
            sql.setString(2,titulo);
            sql.setString(3,d.getSitio());
            resultado=sql.executeUpdate();
        }
        else{
            resultado=0;
        }                   
        return resultado;
    }
    
    public int guardarImagen(String imagen, String direccion) throws Exception{ 
        int resultado;
        String titulo;
        if(imagen.length()<=191){
            PreparedStatement sql=conexion.prepareStatement("insert into imagenes values (?,?)");
            sql.setString(1,imagen);
            sql.setString(2,direccion);
            resultado=sql.executeUpdate();   
        }
        else{
            resultado=0;
        }                   
        return resultado;
    }
    
    public int eliminarSitio(String s) throws Exception{
         PreparedStatement sql=conexion.prepareStatement("delete from sitios where direccion=?");
        sql.setString(1,s);
        int resultado=sql.executeUpdate();             
        return resultado;
    }
    
    public int eliminarDireccion(Direccion d) throws Exception{
         PreparedStatement sql=conexion.prepareStatement("delete from direcciones where direccion=?");
        sql.setString(1,d.getDireccion());
        int resultado=sql.executeUpdate();             
        return resultado;
    }
    
    public int eliminarImagen(String imagen, String direccion) throws Exception{
        PreparedStatement sql=conexion.prepareStatement("delete from imagenes where imagen=? and direccion=?");
        sql.setString(1,imagen);
        sql.setString(2, direccion);
        int resultado=sql.executeUpdate();             
        return resultado;
    }
    
    public int actualizarTitulo(Direccion d) throws Exception{
        String titulo=d.getTitulo();
        if(titulo.length()>191){
            titulo=titulo.substring(0,191);
        }
        PreparedStatement sql=conexion.prepareStatement("update direcciones set titulo='"+titulo+"' where direccion=?");
        sql.setString(1,d.getDireccion());
        int resultado=sql.executeUpdate();                       
        return resultado;
    }
    
    public int actualizarFechaExpiracion(String sitio) throws Exception{ 
        SimpleDateFormat formateador = new SimpleDateFormat("yyyy-MM-dd hh:mm:ss");
        String fecha=formateador.format(Fecha.sumarDiasFecha(new Date(), 2));
        PreparedStatement sql=conexion.prepareStatement("update sitios set fecha_exp='"+fecha+"' where direccion=?");
        
        
        sql.setString(1,sitio);
        int resultado=sql.executeUpdate();                       
        return resultado;
    }
    public void guardarKeyWords(Map<String,Integer> keywords, String direccion) throws Exception{ 
        PreparedStatement sql=conexion.prepareStatement("insert into keywords values (?,?,?)");
        String keyword;
        if(direccion.length()<=191){
            for (Map.Entry<String, Integer> entry : keywords.entrySet()) {
                keyword=entry.getKey();
                if(keyword.length()>191){
                    keyword=keyword.substring(0,191);
                }
                sql.setString(1,keyword);
                sql.setString(2,direccion);
                sql.setString(3,entry.getValue().toString());
                sql.executeUpdate();  
            }    
        }
                                 
    }
    public void eliminarKeywords(ArrayList<Palabra> palabras) throws Exception{
        PreparedStatement sql=conexion.prepareStatement("delete from keywords where keyword=? and direccion=?");
        for(int i=0;i<palabras.size();i++){
            sql.setString(1,palabras.get(i).getKeyword());
            sql.setString(2,palabras.get(i).getDireccion());
        }        
        int resultado=sql.executeUpdate();
    }
    public int actualizarPrioridadKeyword(Palabra p) throws Exception{ 
        PreparedStatement sql=conexion.prepareStatement("update keywords set prioridad='"+p.getPrioridad()+"' where keyword=? and direccion=?");     
        sql.setString(1,p.getKeyword());
        sql.setString(2,p.getDireccion());
        int resultado=sql.executeUpdate();                       
        return resultado;                           
    }
    public ArrayList<Palabra> listadoKeywordsPorDireccion(String direccion) throws Exception{
        Statement sql = conexion.createStatement(); 
        ResultSet rs = sql.executeQuery ("SELECT keyword,direccion,prioridad FROM keywords WHERE direccion='"+direccion+"'");             
        ArrayList<Palabra> v=new ArrayList();
        while(rs.next()){
            v.add(new Palabra(rs.getString("keyword"),rs.getString("direccion"),Integer.parseInt(rs.getString("prioridad"))));                
        }        
        return v;
    }
    public ArrayList<String> listadoImagenesPorDireccion(String direccion) throws Exception{
        Statement sql = conexion.createStatement(); 
        ResultSet rs = sql.executeQuery ("SELECT imagen FROM imagenes WHERE direccion='"+direccion+"'");             
        ArrayList<String> v=new ArrayList();
        while(rs.next()){
            v.add(rs.getString("imagen"));                
        }        
        return v;
    }
}

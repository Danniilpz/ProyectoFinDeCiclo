package bot;

public class Direccion {
    private String sitio;
    private String direccion;
    private String titulo;

    public Direccion(String sitio, String direccion, String titulo) {
        this.sitio = sitio;
        this.direccion = direccion;
        this.titulo=titulo;
    }
    
    public Direccion(String sitio, String direccion) {
        this.sitio = sitio;
        this.direccion = direccion;
        this.titulo="";
    }

    public String getSitio() {
        return sitio;
    }

    public String getDireccion() {
        return direccion;
    }

    public String getTitulo() {
        return titulo;
    }

    public void setSitio(String sitio) {
        this.sitio = sitio;
    }

    public void setDireccion(String direccion) {
        this.direccion = direccion;
    }

    public void setTitulo(String titulo) {
        this.titulo = titulo;
    }
    
}

package bot;

public class Palabra {
    private String keyword;
    private String direccion;
    private int prioridad;

    public Palabra(String keyword, String direccion, int prioridad) {
        this.keyword = keyword;
        this.direccion = direccion;
        this.prioridad = prioridad;
    }

    public String getKeyword() {
        return keyword;
    }

    public String getDireccion() {
        return direccion;
    }

    public int getPrioridad() {
        return prioridad;
    }

    public void setKeyword(String keyword) {
        this.keyword = keyword;
    }

    public void setDireccion(String direccion) {
        this.direccion = direccion;
    }

    public void setPrioridad(int prioridad) {
        this.prioridad = prioridad;
    }

    @Override
    public int hashCode() {
        int hash = 5;
        return hash;
    }

    @Override
    public boolean equals(Object obj) {
        if (obj == null) {
            return false;
        }
        if (getClass() != obj.getClass()) {
            return false;
        }
        final Palabra other = (Palabra) obj;
        if (!this.keyword.equalsIgnoreCase(other.keyword)) {
            return false;
        }
        if (!this.direccion.equalsIgnoreCase(other.direccion)) {
            return false;
        }
        return true;
    }
    
}

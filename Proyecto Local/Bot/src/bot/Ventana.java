
package bot;

import java.awt.Dimension;
import java.awt.Graphics;
import java.awt.Image;
import java.awt.Toolkit;
import java.io.IOException;
import java.util.logging.Level;
import java.util.logging.Logger;
import javax.imageio.ImageIO;
import javax.swing.JFrame;
import javax.swing.JLabel;
import javax.swing.JOptionPane;
import javax.swing.JPanel;

/**
 *
 * @author Dani
 */
public class Ventana extends JFrame{
    Panel p;
    Ventana() {
        Toolkit t=Toolkit.getDefaultToolkit();
        Dimension n=t.getScreenSize();
        int altura=n.height;
        int anchura=n.width;
        setBounds(altura/4,anchura/4,400,200);
        p=new Panel();
        add(p);
        setTitle("Robot Loopz");
        setVisible(true);
        setResizable(false);
        setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
        Image icon=t.getImage(getClass().getResource("/images/icologo.png"));
        setIconImage(icon);
        addWindowListener(new java.awt.event.WindowAdapter() {
            @Override
            public void windowClosing(java.awt.event.WindowEvent evt) {
                close(); //Función con acciones que se ejecutan antes de cerrar el programa, para evitar errores importantes en la base de datos.
            }
        });
    }
    private void close(){ 
        if (JOptionPane.showConfirmDialog(rootPane, "¿Estás seguro de cerrar el programa?\nNo atenderá al fichero robot_status.",
                "Salir del sistema", JOptionPane.YES_NO_OPTION) == JOptionPane.YES_OPTION){
            try {
                Bot.dao.desbloquearTodosSitios(); //Se desbloquean todos los sitios bloqueados, para evitar que se queden bloquedado permanentemente.
                Bot.dao.cerrarConexion(); //Se cierra la conexión de la clase principal.
            } catch (Exception ex) {
                Logger.getLogger(Ventana.class.getName()).log(Level.SEVERE, null, ex);
            }
            finally{
                System.exit(0);
            }
            
        }
    } 
}
class Panel extends JPanel{
    private Image img;
    private JLabel label;
    public Panel() {
        label=new JLabel("");
        add(label);
    }
    @Override
    public void paintComponent(Graphics g){
        super.paintComponent(g);
        try {
            img=ImageIO.read(getClass().getResource("/images/logo.png"));
        } catch (IOException ex) {
            System.out.println("La imagen no se encuentra.");
        }
        g.drawImage(img, 20, 15, null);
        
    }
    public void setOn(){
        label.setText("Se está ejecutando el robot");
    }
    public void setOff(){
        label.setText("El robot está detenido");
    }
}

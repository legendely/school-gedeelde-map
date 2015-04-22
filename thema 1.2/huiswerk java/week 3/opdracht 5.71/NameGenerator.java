import java.util.*;

public class NameGenerator
{
    private String firstname;
    private String lastname;
    private String mother_maiden_name;
    private String town_of_birth;
    private String swfirstname;
    private String swlastname;
    
    public NameGenerator(String F_name, String L_name, String M_name, String C_name)
    {
        firstname = F_name;
        lastname = L_name;
        mother_maiden_name = M_name;
        town_of_birth = C_name;
    }

    public String getFirstname()
    {
        return firstname;
    }
    
    public String getLastname()
    {
        return lastname;
    }
    
    public String getMother_maiden_name()
    {
        return mother_maiden_name;
    }
    
    public String getTown_of_birth()
    {
        return town_of_birth;
    }
    
    public void setFirstname(String F_name)
    {
        firstname = F_name;
    }
    
        public void setLastname(String L_name)
    {
        lastname = L_name;
    }
    
    public void setMothername(String M_name)
    {
        mother_maiden_name = M_name;
    }
    
        public void setCityname(String C_name)
    {
        town_of_birth = C_name;
    }
    
    
    public void generateStarwarsname()
    {
        String str_f = firstname;
        String str_l = lastname;
        String result_f = str_f.substring(0, 3);
        String result_l = str_l.substring(0, 2);
        swfirstname = result_f + result_l;
        
        String str_m = mother_maiden_name;
        String str_c = town_of_birth;
        String result_m = str_m.substring(0, 2);
        String result_c = str_c.substring(0, 3);
        swlastname = result_m + result_c;
       
        String swname = swfirstname + " " + swlastname;
        
        System.out.println(swname);
    }
    
}

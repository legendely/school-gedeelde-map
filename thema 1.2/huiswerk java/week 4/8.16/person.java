public class person
{
   protected String name;
   protected String adress;
   protected int phonenumber;
    
    public person (String name, String adress, int phonenumber)
    {
        this.name = name;
        this.adress = adress;
        this.phonenumber = phonenumber;
    }
    
    public String getName()
    {
        return name;
    }
    
    public String getAdress()
    {
        return adress;
    }
    
    public int getphonenumber()
    {
        return phonenumber;
    }
    
    public void setName(String name)
    {
        this.name = name;
    }
    
    public void setAdress(String adress)
    {
        this.adress = adress;
    }

    public void setPhonenumber(int phonenumber)
    {
        this.phonenumber = phonenumber;
    }
    
    
}


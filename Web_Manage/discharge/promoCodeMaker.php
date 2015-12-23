<?php
#include <cstdlib> 
#include <iostream> 
#include <fcntl.h>  

#define ASCII_NUM 3 
#define MULTIPLY_VALUE 2 

using namespace std; 

int main(int argc, char *argv[]) 
{ 
int i, no=0; 
char key[ASCII_NUM+ASCII_NUM*MULTIPLY_VALUE+2]="0"; //計: 3個英文 + "-" + 6個阿拉伯數字 + '\0' 
char *name; 
int handle; 

cout << "Name: "; 
gets(name); 
for(i=0; i<strlen(name) ;i++){ 
    //cout << (int)name[i] << endl; 
    no+=name[i]; 
} 
//cout << no << endl; 

try{ 
    handle= open("key.exe", O_BINARY); 
    srand(filelength(handle)+no); 
    close(handle); 
} 
catch(std::exception &e){ } 

for(int j=0; j<3; j++){ 
    no=0; 
     
    for(i=0; i<ASCII_NUM ;i++){ 
      key[no++]= (char)(rand()%26 +65); //轉成英文字母  
    } 
    key[no++]= '-'; 
    for(i=0; i<(ASCII_NUM*MULTIPLY_VALUE) ;i++){ 
      key[no++]= (char)(rand()%10 +48); //轉成阿拉伯數字  
    } 

    cout << "Serial is: " << key << endl; 
} 
    system("PAUSE"); 
    return EXIT_SUCCESS; 
}  
?>
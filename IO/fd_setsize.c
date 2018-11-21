#include <stdio.h>
#include <sys/select.h>

int main()
{
    printf("%d\n", FD_SETSIZE);
    return 0;
}
